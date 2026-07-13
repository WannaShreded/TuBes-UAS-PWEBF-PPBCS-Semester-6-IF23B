import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

import '../../models/payroll_method.dart';
import '../../services/api_client.dart';
import '../../services/auth_service.dart';
import '../../theme/app_theme.dart';
import '../../widgets/common_widgets.dart';

class EmployeePayrollPage extends StatefulWidget {
  const EmployeePayrollPage({super.key});

  @override
  State<EmployeePayrollPage> createState() => _EmployeePayrollPageState();
}

class _EmployeePayrollPageState extends State<EmployeePayrollPage> {
  List<PayrollMethod> _methods = [];
  int? _selectedId;
  bool _loading = true;
  bool _saving = false;
  final _bankController = TextEditingController();
  final _walletController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _load();
  }

  @override
  void dispose() {
    _bankController.dispose();
    _walletController.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    final token = await AuthService().getToken();
    final response = await http.get(
      Uri.parse('${ApiClient.baseUrl}/employee/payroll-methods'),
      headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );
    final body = jsonDecode(response.body) as Map<String, dynamic>;
    if (response.statusCode != 200) {
      throw Exception(body['message'] ?? 'Gagal memuat metode.');
    }

    if (!mounted) return;
    setState(() {
      _methods = (body['data'] as List)
          .map((item) => PayrollMethod.fromJson(item))
          .toList();
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

    setState(() => _saving = true);

    final token = await AuthService().getToken();
    final response = await http.put(
      Uri.parse('${ApiClient.baseUrl}/employee/payroll-method'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
      body: jsonEncode({
        'payroll_method_id': _selectedId,
        'nomor_rekening': _bankController.text.trim(),
        'nomor_e_wallet': _walletController.text.trim(),
      }),
    );

    final body = jsonDecode(response.body) as Map<String, dynamic>;

    if (!mounted) return;
    setState(() => _saving = false);

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          body['message'] ?? (response.statusCode == 200
              ? 'Berhasil disimpan'
              : 'Gagal menyimpan'),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) {
      return const Scaffold(
        body: Center(child: CircularProgressIndicator()),
      );
    }

    final type = _selected?.type.toLowerCase() ?? '';
    final isBank = type.contains('bank');
    final isWallet = type.contains('wallet');

    return Scaffold(
      appBar: AppBar(title: const Text('Metode Gaji Saya')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(AppSpacing.md),
        child: FormCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const FormSectionLabel("Metode Pembayaran"),
              DropdownButtonFormField<int>(
                initialValue: _selectedId,
                decoration: const InputDecoration(
                  labelText: 'Metode Gaji',
                  prefixIcon: Icon(Icons.account_balance_wallet_outlined),
                ),
                items: _methods
                    .map(
                      (method) => DropdownMenuItem(
                        value: method.id,
                        child: Text(method.name),
                      ),
                    )
                    .toList(),
                onChanged: (value) => setState(() => _selectedId = value),
              ),
              if (isBank) ...[
                const SizedBox(height: AppSpacing.md),
                TextField(
                  controller: _bankController,
                  decoration: const InputDecoration(
                    labelText: 'Nomor Rekening',
                    prefixIcon: Icon(Icons.credit_card_outlined),
                  ),
                ),
              ],
              if (isWallet) ...[
                const SizedBox(height: AppSpacing.md),
                TextField(
                  controller: _walletController,
                  decoration: const InputDecoration(
                    labelText: 'Nomor E-Wallet',
                    prefixIcon: Icon(Icons.phone_android_outlined),
                  ),
                ),
              ],
              const SizedBox(height: AppSpacing.lg),
              SizedBox(
                height: 48,
                child: ElevatedButton(
                  onPressed: _saving ? null : _save,
                  child: _saving
                      ? const SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            color: Colors.white,
                          ),
                        )
                      : const Text('Simpan'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}