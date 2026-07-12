import 'package:flutter/material.dart';

import '../../services/jabatan_service.dart';
import '../../theme/app_theme.dart';
import '../../widgets/common_widgets.dart';

class CreateJabatanPage extends StatefulWidget {
  const CreateJabatanPage({super.key});

  @override
  State<CreateJabatanPage> createState() => _CreateJabatanPageState();
}

class _CreateJabatanPageState extends State<CreateJabatanPage> {
  final _formKey = GlobalKey<FormState>();

  final nameController = TextEditingController();
  final salaryController = TextEditingController();
  final descriptionController = TextEditingController();
  final JabatanService _service = JabatanService();

  bool _isLoading = false;

  @override
  void dispose() {
    nameController.dispose();
    salaryController.dispose();
    descriptionController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    final success = await _service.create(
      name: nameController.text,
      salary: int.parse(salaryController.text),
      description: descriptionController.text,
    );

    if (!mounted) return;

    setState(() => _isLoading = false);

    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Jabatan berhasil ditambahkan")),
      );
      Navigator.pop(context, true);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Gagal menambahkan jabatan")),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Tambah Jabatan")),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(AppSpacing.md),
        child: Form(
          key: _formKey,
          child: FormCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const FormSectionLabel("Informasi Jabatan"),
                TextFormField(
                  controller: nameController,
                  decoration: const InputDecoration(
                    labelText: "Nama Jabatan",
                    prefixIcon: Icon(Icons.badge_outlined),
                  ),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return "Nama jabatan wajib diisi";
                    }
                    return null;
                  },
                ),
                const SizedBox(height: AppSpacing.md),
                TextFormField(
                  controller: salaryController,
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(
                    labelText: "Gaji",
                    prefixIcon: Icon(Icons.payments_outlined),
                    prefixText: "Rp ",
                  ),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return "Gaji wajib diisi";
                    }
                    if (int.tryParse(value) == null) {
                      return "Gaji harus berupa angka";
                    }
                    return null;
                  },
                ),
                const SizedBox(height: AppSpacing.md),
                TextFormField(
                  controller: descriptionController,
                  maxLines: 4,
                  decoration: const InputDecoration(
                    labelText: "Deskripsi",
                    alignLabelWithHint: true,
                  ),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return "Deskripsi wajib diisi";
                    }
                    return null;
                  },
                ),
                const SizedBox(height: AppSpacing.lg),
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