import 'package:flutter/material.dart';

import '../../models/employee.dart';
import '../../services/employee_service.dart';
import '../employee/create_employee_page.dart';
import '../employee/edit_employee_page.dart';

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
            return Center(child: Text(snapshot.error.toString()));
          }

          final employees = snapshot.data!;

          if (employees.isEmpty) {
            return const Center(child: Text("Belum ada data karyawan"));
          }

          return ListView.builder(
            itemCount: employees.length,
            itemBuilder: (context, index) {
              final employee = employees[index];

              return Card(
                margin: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                child: ListTile(
                  title: Text(employee.namaLengkap),

                  subtitle: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text("ID : ${employee.idPekerja}"),
                      Text("NIK : ${employee.nik}"),
                      Text("Jabatan : ${employee.jabatan}"),
                      Text("Role : ${employee.role}"),

                      Text(
                        employee.isActive
                            ? "Status : Aktif"
                            : "Status : Nonaktif",
                      ),
                    ],
                  ),

                  onTap: () async {
                    final result = await Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => EditEmployeePage(employee: employee),
                      ),
                    );

                    if (result == true) {
                      setState(() {
                        futureEmployee = _service.getAll();
                      });
                    }
                  },

                  trailing: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      IconButton(
                        icon: const Icon(Icons.edit, color: Colors.blue),
                        onPressed: () async {
                          final result = await Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (_) =>
                                  EditEmployeePage(employee: employee),
                            ),
                          );

                          if (result == true) {
                            setState(() {
                              futureEmployee = _service.getAll();
                            });
                          }
                        },
                      ),
                      IconButton(
                        icon: const Icon(Icons.delete, color: Colors.red),
                        onPressed: () async {
                          final confirm = await showDialog<bool>(
                            context: context,
                            builder: (_) => AlertDialog(
                              title: const Text("Konfirmasi"),
                              content: Text("Hapus ${employee.namaLengkap} ?"),
                              actions: [
                                TextButton(
                                  onPressed: () {
                                    Navigator.pop(context, false);
                                  },
                                  child: const Text("Batal"),
                                ),
                                ElevatedButton(
                                  onPressed: () {
                                    Navigator.pop(context, true);
                                  },
                                  child: const Text("Hapus"),
                                ),
                              ],
                            ),
                          );

                          if (confirm != true) return;

                          final success = await _service.delete(employee.id);

                          if (success) {
                            setState(() {
                              futureEmployee = _service.getAll();
                            });

                            if (!mounted) return;

                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text("Karyawan berhasil dihapus"),
                              ),
                            );
                          }
                        },
                      ),
                    ],
                  ),
                ),
              );
            },
          );
        },
      ),
      floatingActionButton: FloatingActionButton(
        child: const Icon(Icons.add),
        onPressed: () async {
          final result = await Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => const CreateEmployeePage()),
          );

          if (result == true) {
            setState(() {
              futureEmployee = _service.getAll();
            });
          }
        },
      ),
    );
  }
}
