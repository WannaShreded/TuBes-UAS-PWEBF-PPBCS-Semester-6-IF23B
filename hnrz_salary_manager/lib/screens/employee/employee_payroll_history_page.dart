import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

import '../../models/payroll_history.dart';
import '../../services/payroll_history_service.dart';
import '../../theme/app_theme.dart';

class EmployeePayrollHistoryPage extends StatefulWidget {
  const EmployeePayrollHistoryPage({super.key});

  @override
  State<EmployeePayrollHistoryPage> createState() => _EmployeePayrollHistoryPageState();
}

class _EmployeePayrollHistoryPageState extends State<EmployeePayrollHistoryPage> {
  final _service = PayrollHistoryService();
  late Future<EmployeePayrollHistoryResponse> _futureData;

  final _currencyFormatter = NumberFormat.currency(
    locale: 'id_ID',
    symbol: 'Rp ',
    decimalDigits: 0,
  );

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  void _loadData() {
    setState(() {
      _futureData = _service.getMyPayrollHistory();
    });
  }

  Future<void> _refresh() async {
    _loadData();
    await _futureData;
  }

  String _formatPeriod(String period) {
    try {
      final parts = period.split('-');
      if (parts.length == 2) {
        final year = parts[0];
        final month = int.parse(parts[1]);
        const months = [
          'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
          'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        if (month >= 1 && month <= 12) {
          return "${months[month - 1]} $year";
        }
      }
    } catch (_) {}
    return period;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Riwayat Gaji Saya'),
      ),
      body: RefreshIndicator(
        onRefresh: _refresh,
        child: FutureBuilder<EmployeePayrollHistoryResponse>(
          future: _futureData,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            }

            if (snapshot.hasError) {
              return ListView(
                children: [
                  SizedBox(
                    height: MediaQuery.of(context).size.height * 0.7,
                    child: Center(
                      child: Padding(
                        padding: const EdgeInsets.all(AppSpacing.lg),
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Icon(
                              Icons.error_outline,
                              color: AppColors.danger,
                              size: 48,
                            ),
                            const SizedBox(height: AppSpacing.sm),
                            Text(
                              "Gagal Memuat Data",
                              style: Theme.of(context).textTheme.titleLarge,
                            ),
                            const SizedBox(height: AppSpacing.xs),
                            Text(
                              "${snapshot.error}",
                              textAlign: TextAlign.center,
                              style: const TextStyle(color: AppColors.textSecondary),
                            ),
                            const SizedBox(height: AppSpacing.md),
                            ElevatedButton(
                              onPressed: _loadData,
                              child: const Text("Coba Lagi"),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                ],
              );
            }

            final data = snapshot.data!;
            final histories = data.histories;

            if (histories.isEmpty) {
              return ListView(
                children: [
                  SizedBox(
                    height: MediaQuery.of(context).size.height * 0.7,
                    child: const Center(
                      child: Padding(
                        padding: EdgeInsets.all(AppSpacing.lg),
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(
                              Icons.payments_outlined,
                              color: AppColors.textDisabled,
                              size: 64,
                            ),
                            SizedBox(height: AppSpacing.sm),
                            Text(
                              "Belum Ada Riwayat",
                              style: TextStyle(
                                fontSize: 16,
                                fontWeight: FontWeight.bold,
                                color: AppColors.textSecondary,
                              ),
                            ),
                            SizedBox(height: AppSpacing.xs),
                            Text(
                              "Penerimaan gaji Anda akan tercatat di sini setelah diproses oleh Admin.",
                              textAlign: TextAlign.center,
                              style: TextStyle(color: AppColors.textDisabled),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                ],
              );
            }

            return ListView(
              padding: const EdgeInsets.all(AppSpacing.md),
              children: [
                // Card Ringkasan Statistik
                Row(
                  children: [
                    Expanded(
                      child: StatCard(
                        title: "Total Gaji Diterima",
                        value: _currencyFormatter.format(data.totalPayroll),
                        color: AppColors.statGreen,
                        footer: "Dari gaji yang lunas",
                        footerIcon: Icons.check_circle_outline,
                      ),
                    ),
                    const SizedBox(width: AppSpacing.md),
                    Expanded(
                      child: StatCard(
                        title: "Rata-rata Gaji",
                        value: _currencyFormatter.format(data.averagePayroll),
                        color: AppColors.statBlue,
                        footer: "Per periode",
                        footerIcon: Icons.analytics_outlined,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: AppSpacing.lg),
                Text(
                  "Daftar Slip Gaji",
                  style: Theme.of(context).textTheme.titleLarge,
                ),
                const SizedBox(height: AppSpacing.sm),
                // List Slip Gaji
                ...histories.map((history) {
                  final isPaid = history.paymentStatus == 'Sudah Dibayar';

                  return Card(
                    margin: const EdgeInsets.only(bottom: AppSpacing.sm),
                    child: Theme(
                      data: Theme.of(context).copyWith(
                        dividerColor: Colors.transparent,
                      ),
                      child: ExpansionTile(
                        tilePadding: const EdgeInsets.symmetric(
                          horizontal: AppSpacing.md,
                          vertical: AppSpacing.xs,
                        ),
                        leading: IconAvatar(
                          icon: Icons.history_outlined,
                          color: isPaid ? AppColors.statGreen : AppColors.textDisabled,
                        ),
                        title: Text(
                          _formatPeriod(history.payrollPeriod),
                          style: Theme.of(context).textTheme.titleMedium,
                        ),
                        subtitle: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const SizedBox(height: 2),
                            Text(
                              "Total: ${_currencyFormatter.format(history.totalDibayarkan)}",
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                                color: AppColors.primary,
                              ),
                            ),
                            const SizedBox(height: 6),
                            StatusBadge(
                              label: history.paymentStatus,
                              type: isPaid ? StatusType.success : StatusType.danger,
                            ),
                          ],
                        ),
                        children: [
                          const Divider(height: 1),
                          Padding(
                            padding: const EdgeInsets.all(AppSpacing.md),
                            child: Column(
                              children: [
                                _buildDetailRow("Jabatan", history.jabatan),
                                _buildDetailRow(
                                  "Gaji Pokok",
                                  _currencyFormatter.format(history.gajiPokok),
                                ),
                                _buildDetailRow(
                                  "Bonus",
                                  _currencyFormatter.format(history.bonus),
                                ),
                                _buildDetailRow(
                                  "Metode Penggajian",
                                  history.paymentMethod,
                                ),
                                if (history.paymentDate != null)
                                  _buildDetailRow(
                                    "Tanggal Dibayar",
                                    history.paymentDate!,
                                  ),
                                if (history.notes != null && history.notes!.isNotEmpty)
                                  _buildDetailRow("Catatan", history.notes!),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  );
                }),
              ],
            );
          },
        ),
      ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: const TextStyle(
              color: AppColors.textSecondary,
              fontSize: 13,
            ),
          ),
          Text(
            value,
            style: const TextStyle(
              fontWeight: FontWeight.w600,
              color: AppColors.textPrimary,
              fontSize: 13,
            ),
          ),
        ],
      ),
    );
  }
}
