import 'package:flutter/material.dart';

import '../../models/employee.dart';
import '../../services/employee_service.dart';
import '../../theme/app_theme.dart';
import '../../widgets/common_widgets.dart';
import 'create_employee_page.dart';
import 'edit_employee_page.dart';

class EmployeePage extends StatefulWidget {
  const EmployeePage({super.key});

  @override
  State<EmployeePage> createState() => _EmployeePageState();
}

class _EmployeePageState extends State<EmployeePage> {
  final EmployeeService _service = EmployeeService();

  late Future<List<Employee>> futureEmployee;

  @override
  void initState() {
    super.initState();
    futureEmployee = _service.getAll();
  }

  Future<void> _refresh() async {
    setState(() {
      futureEmployee = _service.getAll();
    });
  }

  Future<void> _confirmDelete(Employee employee) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(AppRadius.md),
        ),
        title: const Text("Konfirmasi Hapus"),
        content: Text("Hapus ${employee.namaLengkap}?"),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text("Batal"),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.danger),
            onPressed: () => Navigator.pop(context, true),
            child: const Text("Hapus"),
          ),
        ],
      ),
    );

    if (confirm != true) return;

    final success = await _service.delete(employee.id);

    if (success) {
      await _refresh();
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Karyawan berhasil dihapus")),
      );
    } else {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Gagal menghapus karyawan")),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Data Karyawan")),
      body: FutureBuilder<List<Employee>>(
        future: futureEmployee,
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

          final employees = snapshot.data ?? [];

          if (employees.isEmpty) {
            return RefreshIndicator(
              onRefresh: _refresh,
              child: ListView(
                children: [
                  SizedBox(
                    height: MediaQuery.of(context).size.height * 0.7,
                    child: const EmptyState(
                      icon: Icons.people_outline,
                      title: "Belum ada data karyawan",
                      message:
                          "Tambahkan karyawan baru menggunakan tombol di bawah.",
                    ),
                  ),
                ],
              ),
            );
          }

          return RefreshIndicator(
            onRefresh: _refresh,
            child: ListView.separated(
              padding: const EdgeInsets.all(AppSpacing.md),
              itemCount: employees.length,
              separatorBuilder: (_, __) =>
                  const SizedBox(height: AppSpacing.sm),
              itemBuilder: (context, index) {
                final employee = employees[index];

                return Card(
                  child: InkWell(
                    borderRadius: BorderRadius.circular(AppRadius.md),
                    onTap: () async {
                      final result = await Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => EditEmployeePage(employee: employee),
                        ),
                      );

                      if (result == true) _refresh();
                    },
                    child: Padding(
                      padding: const EdgeInsets.all(AppSpacing.md),
                      child: Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Container(
                            width: 44,
                            height: 44,
                            decoration: BoxDecoration(
                              color: AppColors.infoBg,
                              borderRadius:
                                  BorderRadius.circular(AppRadius.sm),
                            ),
                            child: const Icon(
                              Icons.person_outline,
                              color: AppColors.primary,
                            ),
                          ),
                          const SizedBox(width: AppSpacing.md),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  employee.namaLengkap,
                                  style:
                                      Theme.of(context).textTheme.titleMedium,
                                ),
                                const SizedBox(height: 2),
                                Text(
                                  "${employee.idPekerja} · NIK ${employee.nik}",
                                  style:
                                      Theme.of(context).textTheme.bodyMedium,
                                ),
                                const SizedBox(height: 6),
                                Wrap(
                                  spacing: AppSpacing.sm,
                                  runSpacing: AppSpacing.xs,
                                  children: [
                                    StatusBadge(
                                      label: employee.jabatan,
                                      type: StatusType.info,
                                    ),
                                    StatusBadge(
                                      label: employee.role,
                                      type: StatusType.warning,
                                    ),
                                    StatusBadge(
                                      label: employee.isActive
                                          ? "Aktif"
                                          : "Nonaktif",
                                      type: employee.isActive
                                          ? StatusType.success
                                          : StatusType.danger,
                                    ),
                                  ],
                                ),
                              ],
                            ),
                          ),
                          IconButton(
                            icon: const Icon(
                              Icons.delete_outline,
                              color: AppColors.danger,
                            ),
                            onPressed: () => _confirmDelete(employee),
                          ),
                        ],
                      ),
                    ),
                  ),
                );
              },
            ),
          );
        },
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () async {
          final result = await Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => const CreateEmployeePage()),
          );

          if (result == true) _refresh();
        },
        child: const Icon(Icons.add),
      ),
    );
  }
}