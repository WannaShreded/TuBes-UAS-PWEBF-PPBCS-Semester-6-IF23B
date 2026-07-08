import 'package:flutter/material.dart';

import '../../models/jabatan.dart';
import '../../services/jabatan_service.dart';

class EditJabatanPage extends StatefulWidget {
  final Jabatan jabatan;

  const EditJabatanPage({
    super.key,
    required this.jabatan,
  });

  @override
  State<EditJabatanPage> createState() => _EditJabatanPageState();
}

class _EditJabatanPageState extends State<EditJabatanPage> {
  final _formKey = GlobalKey<FormState>();

  final _nameController = TextEditingController();
  final _salaryController = TextEditingController();
  final _descriptionController = TextEditingController();

  final JabatanService _service = JabatanService();

  bool _isLoading = false;

  @override
  void initState() {
    super.initState();

    _nameController.text = widget.jabatan.name;
    _salaryController.text = widget.jabatan.salary.toString();
    _descriptionController.text = widget.jabatan.description;
  }

  @override
  void dispose() {
    _nameController.dispose();
    _salaryController.dispose();
    _descriptionController.dispose();
    super.dispose();
  }

  Future<void> _updateJabatan() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _isLoading = true;
    });

    final success = await _service.update(
      id: widget.jabatan.id,
      name: _nameController.text,
      salary: int.parse(_salaryController.text),
      description: _descriptionController.text,
    );

    if (!mounted) return;

    setState(() {
      _isLoading = false;
    });

    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text("Jabatan berhasil diperbarui"),
        ),
      );

      Navigator.pop(context, true);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text("Gagal memperbarui jabatan"),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Edit Jabatan"),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              TextFormField(
                controller: _nameController,
                decoration: const InputDecoration(
                  labelText: "Nama Jabatan",
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return "Nama jabatan wajib diisi";
                  }
                  return null;
                },
              ),

              const SizedBox(height: 20),

              TextFormField(
                controller: _salaryController,
                keyboardType: TextInputType.number,
                decoration: const InputDecoration(
                  labelText: "Gaji",
                  border: OutlineInputBorder(),
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

              const SizedBox(height: 20),

              TextFormField(
                controller: _descriptionController,
                maxLines: 4,
                decoration: const InputDecoration(
                  labelText: "Deskripsi",
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return "Deskripsi wajib diisi";
                  }

                  return null;
                },
              ),

              const SizedBox(height: 30),

              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _isLoading ? null : _updateJabatan,
                  child: _isLoading
                      ? const SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                          ),
                        )
                      : const Text("Update"),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}