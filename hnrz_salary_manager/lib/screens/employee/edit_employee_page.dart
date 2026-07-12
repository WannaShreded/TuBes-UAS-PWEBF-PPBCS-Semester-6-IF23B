import 'package:flutter/material.dart';

import '../../models/jabatan.dart';
import '../../services/api_service.dart';
import '../../services/employee_service.dart';
import '../../services/jabatan_service.dart';
import '../../models/employee.dart';

class EditEmployeePage extends StatefulWidget {
  final Employee employee;

  const EditEmployeePage({super.key, required this.employee});

  @override
  State<EditEmployeePage> createState() => _EditEmployeePageState();
}

class _EditEmployeePageState extends State<EditEmployeePage> {
  final _formKey = GlobalKey<FormState>();

  final namaController = TextEditingController();
  final nikController = TextEditingController();
  final teleponController = TextEditingController();
  final emailController = TextEditingController();
  final alamatController = TextEditingController();

  bool isActive = true;
  bool isLoading = false;

  String? selectedRole;
  Jabatan? selectedJabatan;

  final EmployeeService _employeeService = EmployeeService();
  final JabatanService _jabatanService = JabatanService();

  late Future<List<Jabatan>> futureJabatan;

  @override
  void initState() {
    super.initState();

    futureJabatan = _jabatanService.getAll();

    namaController.text = widget.employee.namaLengkap;
    nikController.text = widget.employee.nik;
    teleponController.text = widget.employee.noTelepon;
    emailController.text = widget.employee.email;
    alamatController.text = widget.employee.alamat;

    selectedRole = widget.employee.role;
    isActive = widget.employee.isActive;
  }

  @override
  void dispose() {
    namaController.dispose();
    nikController.dispose();
    teleponController.dispose();
    emailController.dispose();
    alamatController.dispose();
    super.dispose();
  }

  Future<void> updateEmployee() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    setState(() {
      isLoading = true;
    });

    final success = await _employeeService.update(
      id: widget.employee.id,
      nik: nikController.text.trim(),
      namaLengkap: namaController.text.trim(),
      noTelepon: teleponController.text.trim(),
      email: emailController.text.trim(),
      alamat: alamatController.text.trim(),
      jabatan: selectedJabatan!.name,
      role: selectedRole!,
      isActive: isActive,
    );

    if (!mounted) return;

    setState(() {
      isLoading = false;
    });

    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Karyawan berhasil diperbarui")),
      );

      Navigator.pop(context, true);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Gagal memperbarui karyawan")),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Edit Karyawan")),
      body: FutureBuilder<List<Jabatan>>(
        future: futureJabatan,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }

          if (snapshot.hasError) {
            return Center(child: Text(snapshot.error.toString()));
          }

          final daftarJabatan = snapshot.data!;

          if (selectedJabatan == null) {
            try {
              selectedJabatan = daftarJabatan.firstWhere(
                (j) => j.name == widget.employee.jabatan,
              );
            } catch (_) {}
          }

          return SingleChildScrollView(
            padding: const EdgeInsets.all(20),
            child: Form(
              key: _formKey,
              child: Column(
                children: [
                  // Nama Lengkap
                  TextFormField(
                    controller: namaController,
                    decoration: const InputDecoration(
                      labelText: "Nama Lengkap",
                      border: OutlineInputBorder(),
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return "Nama wajib diisi";
                      }
                      return null;
                    },
                  ),

                  const SizedBox(height: 20),

                  // NIK
                  TextFormField(
                    controller: nikController,
                    keyboardType: TextInputType.number,
                    decoration: const InputDecoration(
                      labelText: "NIK",
                      border: OutlineInputBorder(),
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return "NIK wajib diisi";
                      }
                      return null;
                    },
                  ),

                  const SizedBox(height: 20),

                  // No Telepon
                  TextFormField(
                    controller: teleponController,
                    keyboardType: TextInputType.phone,
                    decoration: const InputDecoration(
                      labelText: "Nomor Telepon",
                      border: OutlineInputBorder(),
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return "Nomor telepon wajib diisi";
                      }
                      return null;
                    },
                  ),

                  const SizedBox(height: 20),

                  // Email
                  TextFormField(
                    controller: emailController,
                    keyboardType: TextInputType.emailAddress,
                    decoration: const InputDecoration(
                      labelText: "Email",
                      border: OutlineInputBorder(),
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return "Email wajib diisi";
                      }

                      if (!value.contains("@")) {
                        return "Format email tidak valid";
                      }

                      return null;
                    },
                  ),

                  const SizedBox(height: 20),

                  // Alamat
                  TextFormField(
                    controller: alamatController,
                    maxLines: 3,
                    decoration: const InputDecoration(
                      labelText: "Alamat",
                      border: OutlineInputBorder(),
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return "Alamat wajib diisi";
                      }
                      return null;
                    },
                  ),

                  const SizedBox(height: 20),

                  // Dropdown Jabatan
                  DropdownButtonFormField<Jabatan>(
                    value: selectedJabatan,
                    decoration: const InputDecoration(
                      labelText: "Jabatan",
                      border: OutlineInputBorder(),
                    ),
                    items: daftarJabatan.map((jabatan) {
                      return DropdownMenuItem(
                        value: jabatan,
                        child: Text(jabatan.name),
                      );
                    }).toList(),
                    onChanged: (value) {
                      setState(() {
                        selectedJabatan = value;
                      });
                    },
                    validator: (value) {
                      if (value == null) {
                        return "Pilih jabatan";
                      }
                      return null;
                    },
                  ),

                  const SizedBox(height: 20),

                  // Dropdown Role
                  DropdownButtonFormField<String>(
                    value: selectedRole,
                    decoration: const InputDecoration(
                      labelText: "Role",
                      border: OutlineInputBorder(),
                    ),
                    items: const [
                      DropdownMenuItem(value: "admin", child: Text("Admin")),

                      DropdownMenuItem(
                        value: "karyawan",
                        child: Text("Karyawan"),
                      ),
                    ],
                    onChanged: (value) {
                      setState(() {
                        selectedRole = value;
                      });
                    },
                    validator: (value) {
                      if (value == null) {
                        return "Pilih role";
                      }
                      return null;
                    },
                  ),

                  const SizedBox(height: 20),

                  // Status
                  SwitchListTile(
                    title: const Text("Status Aktif"),
                    value: isActive,
                    onChanged: (value) {
                      setState(() {
                        isActive = value;
                      });
                    },
                  ),

                  const SizedBox(height: 30),

                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: isLoading ? null : updateEmployee,
                      child: isLoading
                          ? const SizedBox(
                              height: 20,
                              width: 20,
                              child: CircularProgressIndicator(strokeWidth: 2),
                            )
                          : const Text("Update"),
                    ),
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}
