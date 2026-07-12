import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

import '../../models/payroll_method.dart';
import '../../services/api_client.dart';
import '../../services/auth_service.dart';

class EmployeePayrollPage extends StatefulWidget {
  const EmployeePayrollPage({super.key});

  @override
  State<EmployeePayrollPage> createState() => _EmployeePayrollPageState();
}

class _EmployeePayrollPageState extends State<EmployeePayrollPage> {
  List<PayrollMethod> _methods = [];
  int? _selectedId;
  bool _loading = true;
  final _bankController = TextEditingController();
  final _walletController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    final token = await AuthService().getToken();
    final response = await http.get(
      Uri.parse('${ApiClient.baseUrl}/employee/payroll-methods'),
      headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
    );
    final body = jsonDecode(response.body) as Map<String, dynamic>;
    if (response.statusCode != 200) throw Exception(body['message'] ?? 'Gagal memuat metode.');
    if (!mounted) return;
    setState(() {
      _methods = (body['data'] as List).map((item) => PayrollMethod.fromJson(item)).toList();
      _selectedId = body['selected_id'] as int?;
      _bankController.text = body['nomor_rekening']?.toString() ?? '';
      _walletController.text = body['nomor_e_wallet']?.toString() ?? '';
      _loading = false;
    });
  }

  PayrollMethod? get _selected {
    for (final method in _methods) {
      if (method.id == _selectedId) return method;
    }
    return null;
  }

  Future<void> _save() async {
    if (_selectedId == null) return;
    final token = await AuthService().getToken();
    final response = await http.put(
      Uri.parse('${ApiClient.baseUrl}/employee/payroll-method'),
      headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer $token'},
      body: jsonEncode({'payroll_method_id': _selectedId, 'nomor_rekening': _bankController.text.trim(), 'nomor_e_wallet': _walletController.text.trim()}),
    );
    final body = jsonDecode(response.body) as Map<String, dynamic>;
    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(response.statusCode == 200 ? body['message'] : body['message'] ?? 'Gagal menyimpan.')));
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) return const Scaffold(body: Center(child: CircularProgressIndicator()));
    final type = _selected?.type.toLowerCase() ?? '';
    final isBank = type.contains('bank');
    final isWallet = type.contains('wallet');
    return Scaffold(
      appBar: AppBar(title: const Text('My Payroll Method')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(children: [
          DropdownButtonFormField<int>(value: _selectedId, decoration: const InputDecoration(labelText: 'Payroll Method', border: OutlineInputBorder()), items: _methods.map((method) => DropdownMenuItem(value: method.id, child: Text(method.name))).toList(), onChanged: (value) => setState(() => _selectedId = value)),
          if (isBank) Padding(padding: const EdgeInsets.only(top: 16), child: TextField(controller: _bankController, decoration: const InputDecoration(labelText: 'Account Number', border: OutlineInputBorder()))),
          if (isWallet) Padding(padding: const EdgeInsets.only(top: 16), child: TextField(controller: _walletController, decoration: const InputDecoration(labelText: 'E-Wallet Number', border: OutlineInputBorder()))),
          const SizedBox(height: 24),
          SizedBox(width: double.infinity, child: ElevatedButton(onPressed: _save, child: const Text('Save'))),
        ]),
      ),
    );
  }

  @override
  void dispose() { _bankController.dispose(); _walletController.dispose(); super.dispose(); }
}
