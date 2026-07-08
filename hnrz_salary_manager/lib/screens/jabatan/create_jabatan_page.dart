import 'package:flutter/material.dart';
import '../../services/jabatan_service.dart';

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

  @override
  void dispose() {
    nameController.dispose();
    salaryController.dispose();
    descriptionController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Tambah Jabatan")),

      body: Padding(
        padding: const EdgeInsets.all(20),

        child: Form(
          key: _formKey,

          child: Column(
            children: [
              TextFormField(
                controller: nameController,
                decoration: const InputDecoration(labelText: "Nama Jabatan"),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return "Nama jabatan wajib diisi";
                  }
                  return null;
                },
              ),

              const SizedBox(height: 20),

              TextFormField(
                controller: salaryController,
                keyboardType: TextInputType.number,
                decoration: const InputDecoration(labelText: "Gaji"),
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
                controller: descriptionController,
                maxLines: 4,
                decoration: const InputDecoration(labelText: "Deskripsi"),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return "Deskripsi wajib diisi";
                  }

                  return null;
                },
              ),

              const SizedBox(height: 30),

              ElevatedButton(
                onPressed: () async {
                  if (!_formKey.currentState!.validate()) {
                    return;
                  }

                  final success = await _service.create(
                    name: nameController.text,
                    salary: int.parse(salaryController.text),
                    description: descriptionController.text,
                  );

                  if (!mounted) return;

                  if (success) {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(
                        content: Text("Jabatan berhasil ditambahkan"),
                      ),
                    );

                    Navigator.pop(context, true);
                  } else {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(
                        content: Text("Gagal menambahkan jabatan"),
                      ),
                    );
                  }
                },
                child: const Text("Simpan"),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
