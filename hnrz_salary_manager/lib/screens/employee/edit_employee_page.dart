import 'package:flutter/material.dart';

import '../../models/jabatan.dart';
import '../../services/employee_service.dart';
import '../../services/jabatan_service.dart';
import '../../models/employee.dart';
import '../../theme/app_theme.dart';
import '../../widgets/common_widgets.dart';

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
    if (!_formKey.currentState!.validate()) return;

    setState(() => isLoading = true);

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

    setState(() => isLoading = false);

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
            return Center(
              child: Text(
                "Terjadi kesalahan: ${snapshot.error}",
                style: const TextStyle(color: AppColors.danger),
              ),
            );
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
            padding: const EdgeInsets.all(AppSpacing.md),
            child: Form(
              key: _formKey,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  FormCard(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.stretch,
                      children: [
                        const FormSectionLabel("Data Diri"),
                        TextFormField(
                          controller: namaController,
                          decoration: const InputDecoration(
                            labelText: "Nama Lengkap",
                            prefixIcon: Icon(Icons.person_outline),
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
                          controller: nikController,
                          keyboardType: TextInputType.number,
                          decoration: const InputDecoration(
                            labelText: "NIK",
                            prefixIcon: Icon(Icons.badge_outlined),
                          ),
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return "NIK wajib diisi";
                            }
                            return null;
                          },
                        ),
                        const SizedBox(height: AppSpacing.md),
                        TextFormField(
                          controller: teleponController,
                          keyboardType: TextInputType.phone,
                          decoration: const InputDecoration(
                            labelText: "Nomor Telepon",
                            prefixIcon: Icon(Icons.phone_outlined),
                          ),
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return "Nomor telepon wajib diisi";
                            }
                            return null;
                          },
                        ),
                        const SizedBox(height: AppSpacing.md),
                        TextFormField(
                          controller: emailController,
                          keyboardType: TextInputType.emailAddress,
                          decoration: const InputDecoration(
                            labelText: "Email",
                            prefixIcon: Icon(Icons.mail_outline),
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
                        const SizedBox(height: AppSpacing.md),
                        TextFormField(
                          controller: alamatController,
                          maxLines: 3,
                          decoration: const InputDecoration(
                            labelText: "Alamat",
                            alignLabelWithHint: true,
                          ),
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return "Alamat wajib diisi";
                            }
                            return null;
                          },
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: AppSpacing.md),
                  FormCard(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.stretch,
                      children: [
                        const FormSectionLabel("Kepegawaian"),
                        DropdownButtonFormField<Jabatan>(
                          initialValue: selectedJabatan,
                          decoration: const InputDecoration(
                            labelText: "Jabatan",
                            prefixIcon: Icon(Icons.work_outline),
                          ),
                          items: daftarJabatan.map((jabatan) {
                            return DropdownMenuItem(
                              value: jabatan,
                              child: Text(jabatan.name),
                            );
                          }).toList(),
                          onChanged: (value) {
                            setState(() => selectedJabatan = value);
                          },
                          validator: (value) {
                            if (value == null) return "Pilih jabatan";
                            return null;
                          },
                        ),
                        const SizedBox(height: AppSpacing.md),
                        DropdownButtonFormField<String>(
                          initialValue: selectedRole,
                          decoration: const InputDecoration(
                            labelText: "Role",
                            prefixIcon: Icon(Icons.admin_panel_settings_outlined),
                          ),
                          items: const [
                            DropdownMenuItem(
                              value: "admin",
                              child: Text("Admin"),
                            ),
                            DropdownMenuItem(
                              value: "karyawan",
                              child: Text("Karyawan"),
                            ),
                          ],
                          onChanged: (value) {
                            setState(() => selectedRole = value);
                          },
                          validator: (value) {
                            if (value == null) return "Pilih role";
                            return null;
                          },
                        ),
                        const SizedBox(height: AppSpacing.md),
                        SwitchListTile(
                          contentPadding: EdgeInsets.zero,
                          title: const Text("Status Aktif"),
                          value: isActive,
                          onChanged: (value) {
                            setState(() => isActive = value);
                          },
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: AppSpacing.lg),
                  SizedBox(
                    height: 48,
                    child: ElevatedButton(
                      onPressed: isLoading ? null : updateEmployee,
                      child: isLoading
                          ? const SizedBox(
                              height: 20,
                              width: 20,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                color: Colors.white,
                              ),
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