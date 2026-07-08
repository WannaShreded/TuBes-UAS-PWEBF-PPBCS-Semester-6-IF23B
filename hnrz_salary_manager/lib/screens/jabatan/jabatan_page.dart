import 'package:flutter/material.dart';

import '../../models/jabatan.dart';
import '../../services/api_service.dart';
import '../jabatan/create_jabatan_page.dart';
import '../jabatan/edit_jabatan_page.dart';
import '../../services/jabatan_service.dart';

class JabatanPage extends StatefulWidget {
  const JabatanPage({super.key});

  @override
  State<JabatanPage> createState() => _JabatanPageState();
}

class _JabatanPageState extends State<JabatanPage> {
  final JabatanService _service = JabatanService();
  late Future<List<Jabatan>> futureJabatan;

  @override
  void initState() {
    super.initState();
    futureJabatan = _service.getAll();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Data Jabatan")),
      body: FutureBuilder<List<Jabatan>>(
        future: futureJabatan,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }

          if (snapshot.hasError) {
            return Center(child: Text(snapshot.error.toString()));
          }

          final data = snapshot.data!;

          return ListView.builder(
            itemCount: data.length,
            itemBuilder: (context, index) {
              final jabatan = data[index];

              return Card(
                margin: const EdgeInsets.all(10),
                child: ListTile(
                  title: Text(jabatan.name),
                  subtitle: Text(
                    jabatan.description,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  trailing: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text("Rp ${jabatan.salary}"),

                      IconButton(
                        icon: const Icon(Icons.delete, color: Colors.red),
                        onPressed: () async {
                          final confirm = await showDialog<bool>(
                            context: context,
                            builder: (context) {
                              return AlertDialog(
                                title: const Text("Konfirmasi"),
                                content: Text("Hapus jabatan ${jabatan.name}?"),
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

                          final success = await _service.delete(jabatan.id);

                          if (success) {
                            setState(() {
                              futureJabatan = _service.getAll();
                            });

                            if (!mounted) return;

                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text("Jabatan berhasil dihapus"),
                              ),
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
                        builder: (_) => EditJabatanPage(jabatan: jabatan),
                      ),
                    );

                    if (result == true) {
                      setState(() {
                        futureJabatan = ApiService().getJabatan();
                      });
                    }
                  },
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
            MaterialPageRoute(builder: (_) => const CreateJabatanPage()),
          );

          if (result == true) {
            setState(() {
              futureJabatan = ApiService().getJabatan();
            });
          }
        },
      ),
    );
  }
}
