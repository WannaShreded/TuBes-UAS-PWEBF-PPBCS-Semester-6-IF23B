import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:intl/date_symbol_data_local.dart';

import '../../models/bonus.dart';
import '../../services/bonus_service.dart';
import '../bonus/create_bonus_page.dart';
import '../bonus/edit_bonus_page.dart';

class BonusPage extends StatefulWidget {
  const BonusPage({super.key});

  @override
  State<BonusPage> createState() => _BonusPageState();
}

class _BonusPageState extends State<BonusPage> {
  final BonusService _service = BonusService();
  late Future<List<Bonus>> futureBonus;

  String formatPeriode(String date) {
    final parsed = DateTime.parse(date);

    const bulan = [
      '',
      'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember',
    ];

    return '${bulan[parsed.month]} ${parsed.year}';
  }

  @override
  void initState() {
    super.initState();

    initializeDateFormatting('id');

    futureBonus = _service.getAll();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Data Bonus")),
      body: FutureBuilder<List<Bonus>>(
        future: futureBonus,
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
              final bonus = data[index];

              return Card(
                margin: const EdgeInsets.all(10),
                child: ListTile(
                  title: Text(bonus.namaBonus),
                  subtitle: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const SizedBox(height: 5),

                      Text(
                        "Jenis Bonus : ${bonus.jenisBonus}",
                        style: const TextStyle(fontWeight: FontWeight.w500),
                      ),

                      const SizedBox(height: 3),

                      Text(
                        "Periode : ${formatPeriode(bonus.periodeBonus)}",
                        style: const TextStyle(fontWeight: FontWeight.w500),
                      ),

                      const SizedBox(height: 5),

                      Text(
                        bonus.keterangan ?? "",
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ],
                  ),
                  trailing: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text("Rp ${bonus.nominalBonus.toStringAsFixed(0)}"),

                      IconButton(
                        icon: const Icon(Icons.delete, color: Colors.red),
                        onPressed: () async {
                          final confirm = await showDialog<bool>(
                            context: context,
                            builder: (context) {
                              return AlertDialog(
                                title: const Text("Konfirmasi"),
                                content: Text(
                                  "Hapus bonus ${bonus.namaBonus}?",
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

                          final success = await _service.delete(bonus.id);

                          if (success) {
                            setState(() {
                              futureBonus = _service.getAll();
                            });

                            if (!mounted) return;

                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text("Bonus berhasil dihapus"),
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
                        builder: (_) => EditBonusPage(bonus: bonus),
                      ),
                    );

                    if (result == true) {
                      setState(() {
                        futureBonus = _service.getAll();
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
            MaterialPageRoute(builder: (_) => const CreateBonusPage()),
          );

          if (result == true) {
            setState(() {
              futureBonus = _service.getAll();
            });
          }
        },
      ),
    );
  }
}
