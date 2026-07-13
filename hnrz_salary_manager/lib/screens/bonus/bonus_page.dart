import 'package:flutter/material.dart';
import 'package:intl/date_symbol_data_local.dart';

import '../../models/bonus.dart';
import '../../services/bonus_service.dart';
import '../../theme/app_theme.dart';
import '../../widgets/common_widgets.dart';
import 'create_bonus_page.dart';
import 'edit_bonus_page.dart';

class BonusPage extends StatefulWidget {
  const BonusPage({super.key});

  @override
  State<BonusPage> createState() => _BonusPageState();
}

class _BonusPageState extends State<BonusPage> {
  final BonusService _service = BonusService();
  late Future<List<Bonus>> futureBonus;

  static const _cardColors = [
    AppColors.statPurple,
    AppColors.statBlue,
    AppColors.statGreen,
    AppColors.statTeal,
    AppColors.statYellow,
  ];

  static const _bulan = [
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

  String formatPeriode(String date) {
    final parsed = DateTime.parse(date);
    return '${_bulan[parsed.month]} ${parsed.year}';
  }

  String _formatRupiah(num value) {
    final s = value.toStringAsFixed(0);
    final buffer = StringBuffer();
    for (int i = 0; i < s.length; i++) {
      final posFromEnd = s.length - i;
      buffer.write(s[i]);
      if (posFromEnd > 1 && posFromEnd % 3 == 1) buffer.write('.');
    }
    return 'Rp $buffer';
  }

  @override
  void initState() {
    super.initState();
    initializeDateFormatting('id');
    futureBonus = _service.getAll();
  }

  Future<void> _refresh() async {
    setState(() {
      futureBonus = _service.getAll();
    });
  }

  Future<void> _confirmDelete(Bonus bonus) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(AppRadius.md),
        ),
        title: const Text("Konfirmasi Hapus"),
        content: Text("Hapus bonus \"${bonus.namaBonus}\"?"),
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

    final success = await _service.delete(bonus.id);

    if (success) {
      await _refresh();
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Bonus berhasil dihapus")),
      );
    } else {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Gagal menghapus bonus")),
      );
    }
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
            return Center(
              child: Text(
                "Terjadi kesalahan: ${snapshot.error}",
                style: const TextStyle(color: AppColors.danger),
              ),
            );
          }

          final data = snapshot.data ?? [];

          if (data.isEmpty) {
            return RefreshIndicator(
              onRefresh: _refresh,
              child: ListView(
                children: [
                  SizedBox(
                    height: MediaQuery.of(context).size.height * 0.7,
                    child: const EmptyState(
                      icon: Icons.card_giftcard_outlined,
                      title: "Belum ada data bonus",
                      message:
                          "Tambahkan bonus baru menggunakan tombol di bawah.",
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
              itemCount: data.length,
              separatorBuilder: (_, __) =>
                  const SizedBox(height: AppSpacing.sm),
              itemBuilder: (context, index) {
                final bonus = data[index];

                return Card(
                  child: InkWell(
                    borderRadius: BorderRadius.circular(AppRadius.md),
                    onTap: () async {
                      final result = await Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => EditBonusPage(bonus: bonus),
                        ),
                      );
                      if (result == true) _refresh();
                    },
                    child: Padding(
                      padding: const EdgeInsets.all(AppSpacing.md),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              IconAvatar(
                                icon: Icons.card_giftcard_outlined,
                                color: _cardColors[index % _cardColors.length],
                                size: 40,
                              ),
                              const SizedBox(width: AppSpacing.sm),
                              Expanded(
                                child: Text(
                                  bonus.namaBonus,
                                  style:
                                      Theme.of(context).textTheme.titleMedium,
                                ),
                              ),
                              IconButton(
                                icon: const Icon(
                                  Icons.delete_outline,
                                  color: AppColors.danger,
                                ),
                                onPressed: () => _confirmDelete(bonus),
                              ),
                            ],
                          ),
                          const SizedBox(height: AppSpacing.sm),
                          Wrap(
                            spacing: AppSpacing.sm,
                            runSpacing: AppSpacing.xs,
                            children: [
                              StatusBadge(
                                label: bonus.jenisBonus,
                                type: bonus.jenisBonus == "Tetap"
                                    ? StatusType.info
                                    : StatusType.warning,
                              ),
                              StatusBadge(
                                label: formatPeriode(bonus.periodeBonus),
                                type: StatusType.success,
                              ),
                            ],
                          ),
                          if ((bonus.keterangan ?? "").isNotEmpty) ...[
                            const SizedBox(height: AppSpacing.sm),
                            Text(
                              bonus.keterangan!,
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                              style: Theme.of(context).textTheme.bodyMedium,
                            ),
                          ],
                          const SizedBox(height: AppSpacing.sm),
                          Text(
                            _formatRupiah(bonus.nominalBonus),
                            style: const TextStyle(
                              color: AppColors.primary,
                              fontWeight: FontWeight.w700,
                              fontSize: 14,
                            ),
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
            MaterialPageRoute(builder: (_) => const CreateBonusPage()),
          );
          if (result == true) _refresh();
        },
        child: const Icon(Icons.add),
      ),
    );
  }
}