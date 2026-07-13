import 'package:flutter/material.dart';

import '../../models/payroll_method.dart';
import '../../services/payroll_method_service.dart';
import '../../theme/app_theme.dart';
import '../../widgets/common_widgets.dart';
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

  static const _cardColors = [
    AppColors.statGreen,
    AppColors.statBlue,
    AppColors.statYellow,
    AppColors.statPurple,
    AppColors.statTeal,
  ];

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

  Future<void> _confirmDelete(PayrollMethod payroll) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(AppRadius.md),
        ),
        title: const Text("Konfirmasi Hapus"),
        content: Text("Hapus metode penggajian \"${payroll.name}\"?"),
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

    try {
      final success = await _service.delete(payroll.id);

      if (!mounted) return;

      if (success) {
        await _refresh();
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Metode penggajian berhasil dihapus")),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Gagal menghapus metode penggajian")),
        );
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Error: $e")),
      );
    }
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
                    padding: const EdgeInsets.all(AppSpacing.lg),
                    child: Text(
                      "Terjadi kesalahan: ${snapshot.error}",
                      style: const TextStyle(color: AppColors.danger),
                    ),
                  ),
                ],
              );
            }

            final data = snapshot.data ?? [];

            if (data.isEmpty) {
              return ListView(
                children: [
                  SizedBox(
                    height: MediaQuery.of(context).size.height * 0.7,
                    child: const EmptyState(
                      icon: Icons.account_balance_wallet_outlined,
                      title: "Belum ada metode penggajian",
                      message:
                          "Tambahkan metode baru menggunakan tombol di bawah.",
                    ),
                  ),
                ],
              );
            }

            return ListView.separated(
              padding: const EdgeInsets.all(AppSpacing.md),
              itemCount: data.length,
              separatorBuilder: (_, __) => const SizedBox(height: AppSpacing.sm),
              itemBuilder: (context, index) {
                final payroll = data[index];

                return Card(
                  child: InkWell(
                    borderRadius: BorderRadius.circular(AppRadius.md),
                    onTap: () async {
                      final result = await Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => EditPayrollPage(payrollMethod: payroll),
                        ),
                      );

                      if (result == true) await _refresh();
                    },
                    child: Padding(
                      padding: const EdgeInsets.all(AppSpacing.md),
                      child: Row(
                        children: [
                          IconAvatar(
                            icon: Icons.account_balance_wallet_outlined,
                            color: _cardColors[index % _cardColors.length],
                          ),
                          const SizedBox(width: AppSpacing.md),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  payroll.name,
                                  style: Theme.of(context).textTheme.titleMedium,
                                ),
                                const SizedBox(height: 2),
                                Text(
                                  "${payroll.type}${payroll.description != null && payroll.description!.isNotEmpty ? " · ${payroll.description}" : ""}",
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                  style: Theme.of(context).textTheme.bodyMedium,
                                ),
                                const SizedBox(height: 6),
                                StatusBadge(
                                  label: payroll.isActive ? "Aktif" : "Nonaktif",
                                  type: payroll.isActive
                                      ? StatusType.success
                                      : StatusType.danger,
                                ),
                              ],
                            ),
                          ),
                          IconButton(
                            icon: const Icon(
                              Icons.delete_outline,
                              color: AppColors.danger,
                            ),
                            onPressed: () => _confirmDelete(payroll),
                          ),
                        ],
                      ),
                    ),
                  ),
                );
              },
            );
          },
        ),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () async {
          final result = await Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => const CreatePayrollPage()),
          );

          if (result == true) await _refresh();
        },
        child: const Icon(Icons.add),
      ),
    );
  }
}