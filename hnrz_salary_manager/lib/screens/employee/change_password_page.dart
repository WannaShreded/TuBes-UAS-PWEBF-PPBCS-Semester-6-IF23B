import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

import '../../services/api_client.dart';
import '../../services/auth_service.dart';

class ChangePasswordPage extends StatefulWidget {
  const ChangePasswordPage({super.key});

  @override
  State<ChangePasswordPage> createState() => _ChangePasswordPageState();
}

class _ChangePasswordPageState extends State<ChangePasswordPage> {
  final _formKey = GlobalKey<FormState>();
  final _current = TextEditingController();
  final _password = TextEditingController();
  final _confirmation = TextEditingController();
  bool _saving = false;

  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _saving = true);
    final token = await AuthService().getToken();
    final response = await http.put(
      Uri.parse('${ApiClient.baseUrl}/profile/password'),
      headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer $token'},
      body: jsonEncode({'current_password': _current.text, 'password': _password.text, 'password_confirmation': _confirmation.text}),
    );
    final body = jsonDecode(response.body) as Map<String, dynamic>;
    if (!mounted) return;
    setState(() => _saving = false);
    if (response.statusCode == 200) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(body['message'])));
      Navigator.pop(context);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(body['message'] ?? 'Unable to update password.')));
    }
  }

  @override
  Widget build(BuildContext context) => Scaffold(
    appBar: AppBar(title: const Text('Change Password')),
    body: Padding(
      padding: const EdgeInsets.all(16),
      child: Form(
        key: _formKey,
        child: Column(children: [
          _PasswordField(controller: _current, label: 'Current Password'),
          const SizedBox(height: 16),
          _PasswordField(controller: _password, label: 'New Password', minLength: true),
          const SizedBox(height: 16),
          _PasswordField(controller: _confirmation, label: 'Confirm New Password', matches: _password),
          const SizedBox(height: 24),
          SizedBox(width: double.infinity, child: ElevatedButton(onPressed: _saving ? null : _save, child: _saving ? const CircularProgressIndicator() : const Text('Update Password'))),
        ]),
      ),
    ),
  );

  @override
  void dispose() { _current.dispose(); _password.dispose(); _confirmation.dispose(); super.dispose(); }
}

class _PasswordField extends StatelessWidget {
  final TextEditingController controller;
  final String label;
  final bool minLength;
  final TextEditingController? matches;
  const _PasswordField({required this.controller, required this.label, this.minLength = false, this.matches});
  @override
  Widget build(BuildContext context) => TextFormField(
    controller: controller,
    obscureText: true,
    decoration: InputDecoration(labelText: label, border: const OutlineInputBorder()),
    validator: (value) {
      if (value == null || value.isEmpty) return '$label is required.';
      if (minLength && value.length < 8) return 'New password must be at least 8 characters.';
      if (matches != null && value != matches!.text) return 'Passwords do not match.';
      return null;
    },
  );
}
