import 'package:flutter/material.dart';
import '../../services/payroll_method_service.dart';
import '../../theme/app_theme.dart';
import '../../widgets/common_widgets.dart';

class CreatePayrollPage extends StatefulWidget {
  const CreatePayrollPage({super.key});

  @override
  State<CreatePayrollPage> createState() => _CreatePayrollPageState();
}

class _CreatePayrollPageState extends State<CreatePayrollPage> {
  final _formKey = GlobalKey<FormState>();

  final typeController = TextEditingController();
  final nameController = TextEditingController();
  final descriptionController = TextEditingController();
  bool isActive = true;
  bool _isLoading = false;

  final PayrollMethodService _service = PayrollMethodService();

  @override
  void dispose() {
    typeController.dispose();
    nameController.dispose();
    descriptionController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    try {
      final success = await _service.create(
        type: typeController.text,
        name: nameController.text,
        description: descriptionController.text.isEmpty
            ? null
            : descriptionController.text,
        isActive: isActive,
      );

      if (!mounted) return;
      setState(() => _isLoading = false);

      if (success) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text("Metode penggajian berhasil ditambahkan"),
          ),
        );
        Navigator.pop(context, true);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text("Gagal menambahkan metode penggajian"),
          ),
        );
      }
    } catch (e) {
      if (!mounted) return;
      setState(() => _isLoading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Error: $e")),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Tambah Metode Penggajian")),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(AppSpacing.md),
        child: Form(
          key: _formKey,
          child: FormCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const FormSectionLabel("Informasi Metode"),
                TextFormField(
                  controller: typeController,
                  decoration: const InputDecoration(
                    labelText: "Tipe",
                    hintText: "mis. Bank, E-Wallet, Tunai",
                    prefixIcon: Icon(Icons.category_outlined),
                  ),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return "Tipe wajib diisi";
                    }
                    return null;
                  },
                ),
                const SizedBox(height: AppSpacing.md),
                TextFormField(
                  controller: nameController,
                  decoration: const InputDecoration(
                    labelText: "Nama Metode",
                    prefixIcon: Icon(Icons.account_balance_wallet_outlined),
                  ),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return "Nama wajib diisi";
                    }
                    return null;
                  },
                ),
                const SizedBox(height: AppSpacing.md),
                TextFormField(
                  controller: descriptionController,
                  maxLines: 4,
                  decoration: const InputDecoration(
                    labelText: "Deskripsi (opsional)",
                    alignLabelWithHint: true,
                  ),
                ),
                const SizedBox(height: AppSpacing.sm),
                SwitchListTile(
                  contentPadding: EdgeInsets.zero,
                  title: const Text("Aktif"),
                  value: isActive,
                  onChanged: (value) {
                    setState(() => isActive = value);
                  },
                ),
                const SizedBox(height: AppSpacing.md),
                SizedBox(
                  height: 48,
                  child: ElevatedButton(
                    onPressed: _isLoading ? null : _submit,
                    child: _isLoading
                        ? const SizedBox(
                            width: 20,
                            height: 20,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              color: Colors.white,
                            ),
                          )
                        : const Text("Simpan"),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}