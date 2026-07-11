import 'package:flutter/material.dart';

import '../../models/payroll_method.dart';
import '../../services/payroll_method_service.dart';
import 'create_payroll_page.dart';
import 'edit_payroll_page.dart';

class PayrollPage extends StatefulWidget {
  const PayrollPage({super.key});

  @override
  State<PayrollPage> createState() => _PayrollPageState();
}

class _PayrollPageState extends State<PayrollPage> {
  final PayrollMethodService _service = PayrollMethodService();
  late Future<List<PayrollMethod>> futurePayroll;

  @override
  void initState() {
    super.initState();
    futurePayroll = _service.getAll();
  }

  Future<void> _refresh() async {
    setState(() {
      futurePayroll = _service.getAll();
    });
    await futurePayroll;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Metode Penggajian")),
      body: RefreshIndicator(
        onRefresh: _refresh,
        child: FutureBuilder<List<PayrollMethod>>(
          future: futurePayroll,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            }

            if (snapshot.hasError) {
              return ListView(
                children: [
                  Padding(
                    padding: const EdgeInsets.all(20),
                    child: Text(snapshot.error.toString()),
                  ),
                ],
              );
            }

            final data = snapshot.data!;

            if (data.isEmpty) {
              return ListView(
                children: const [
                  Padding(
                    padding: EdgeInsets.all(20),
                    child: Text("Belum ada data metode penggajian"),
                  ),
                ],
              );
            }

            return ListView.builder(
              itemCount: data.length,
              itemBuilder: (context, index) {
                final payroll = data[index];

                return Card(
                  margin: const EdgeInsets.all(10),
                  child: ListTile(
                    title: Text(payroll.name),
                    subtitle: Text(
                      "${payroll.type}${payroll.description != null && payroll.description!.isNotEmpty ? " - ${payroll.description}" : ""}",
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    trailing: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(
                          payroll.isActive ? Icons.check_circle : Icons.cancel,
                          color: payroll.isActive ? Colors.green : Colors.red,
                        ),

                        IconButton(
                          icon: const Icon(Icons.delete, color: Colors.red),
                          onPressed: () async {
                            final confirm = await showDialog<bool>(
                              context: context,
                              builder: (context) {
                                return AlertDialog(
                                  title: const Text("Konfirmasi"),
                                  content: Text(
                                    "Hapus metode penggajian ${payroll.name}?",
                                  ),
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
                                );
                              },
                            );

                            if (confirm != true) return;

                            try {
                              final success = await _service.delete(payroll.id);

                              if (!mounted) return;

                              if (success) {
                                await _refresh();

                                if (!mounted) return;
                                ScaffoldMessenger.of(context).showSnackBar(
                                  const SnackBar(
                                    content: Text(
                                      "Metode penggajian berhasil dihapus",
                                    ),
                                  ),
                                );
                              } else {
                                ScaffoldMessenger.of(context).showSnackBar(
                                  const SnackBar(
                                    content: Text(
                                      "Gagal menghapus metode penggajian",
                                    ),
                                  ),
                                );
                              }
                            } catch (e) {
                              if (!mounted) return;
                              ScaffoldMessenger.of(context).showSnackBar(
                                SnackBar(content: Text("Error: $e")),
                              );
                            }
                          },
                        ),
                      ],
                    ),
                    onTap: () async {
                      final result = await Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => EditPayrollPage(payrollMethod: payroll),
                        ),
                      );

                      if (result == true) {
                        await _refresh();
                      }
                    },
                  ),
                );
              },
            );
          },
        ),
      ),
      floatingActionButton: FloatingActionButton(
        child: const Icon(Icons.add),
        onPressed: () async {
          final result = await Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => const CreatePayrollPage()),
          );

          if (result == true) {
            await _refresh();
          }
        },
      ),
    );
  }
}