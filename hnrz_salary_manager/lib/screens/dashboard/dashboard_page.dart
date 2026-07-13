import 'package:flutter/material.dart';

import '../../models/employee.dart';
import '../../services/employee_service.dart';
import '../../services/jabatan_service.dart';
import '../../services/bonus_service.dart';
import '../../services/payroll_method_service.dart';
import '../../theme/app_theme.dart';
import '../jabatan/jabatan_page.dart';
import '../bonus/bonus_page.dart';
import '../employee/employee_page.dart';
import '../employee/profile_page.dart';
import '../payroll_method/payroll_page.dart';
import '../payroll_method/employee_payroll_page.dart';

class _HomeStats {
  final int aktif;
  final int nonaktif;
  final int jabatan;
  final int bonus;

  const _HomeStats({
    required this.aktif,
    required this.nonaktif,
    required this.jabatan,
    required this.bonus,
  });
}

/// Konten tab "Home" — dipakai di dalam MainShell (sudah ada bottom nav).
class DashboardPage extends StatefulWidget {
  final List<String> roles;

  const DashboardPage({super.key, this.roles = const []});

  @override
  State<DashboardPage> createState() => _DashboardPageState();
}

class _DashboardPageState extends State<DashboardPage> {
  late Future<_HomeStats> _futureStats;

  bool get _isEmployeeOnly =>
      widget.roles.contains('karyawan') && !widget.roles.contains('admin');

  @override
  void initState() {
    super.initState();
    if (!_isEmployeeOnly) {
      _futureStats = _loadStats();
    }
  }

  Future<_HomeStats> _loadStats() async {
    final results = await Future.wait([
      EmployeeService().getAll(),
      JabatanService().getAll(),
      BonusService().getAll(),
    ]);

    final employees = results[0] as List<Employee>;
    final jabatanCount = results[1].length;
    final bonusCount = results[2].length;

    return _HomeStats(
      aktif: employees.where((e) => e.isActive).length,
      nonaktif: employees.where((e) => !e.isActive).length,
      jabatan: jabatanCount,
      bonus: bonusCount,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("HNRZ Salary Manager"),
      ),
      body: SafeArea(
        child: _isEmployeeOnly ? _buildEmployeeHome(context) : _buildAdminHome(context),
      ),
    );
  }

  // ---------------------------------------------------------------------
  // Home untuk role admin: statistik + daftar modul
  // ---------------------------------------------------------------------
  Widget _buildAdminHome(BuildContext context) {
    return RefreshIndicator(
      onRefresh: () async {
        setState(() {
          _futureStats = _loadStats();
        });
        await _futureStats;
      },
      child: ListView(
        padding: const EdgeInsets.all(AppSpacing.md),
        children: [
          Text(
            "Ringkasan",
            style: Theme.of(context).textTheme.headlineSmall,
          ),
          const SizedBox(height: AppSpacing.md),
          FutureBuilder<_HomeStats>(
            future: _futureStats,
            builder: (context, snapshot) {
              if (snapshot.connectionState == ConnectionState.waiting) {
                return const SizedBox(
                  height: 180,
                  child: Center(child: CircularProgressIndicator()),
                );
              }

              if (snapshot.hasError) {
                return Text(
                  "Gagal memuat ringkasan: ${snapshot.error}",
                  style: const TextStyle(color: AppColors.danger),
                );
              }

              final stats = snapshot.data!;

              return GridView.count(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                crossAxisCount: 2,
                crossAxisSpacing: AppSpacing.md,
                mainAxisSpacing: AppSpacing.md,
                childAspectRatio: 1.5,
                children: [
                  StatCard(
                    title: "Karyawan Aktif",
                    value: "${stats.aktif}",
                    color: AppColors.statGreen,
                    footer: "Sedang bekerja",
                    footerIcon: Icons.trending_up,
                  ),
                  StatCard(
                    title: "Karyawan Nonaktif",
                    value: "${stats.nonaktif}",
                    color: AppColors.statRed,
                    footer: "Tidak aktif",
                    footerIcon: Icons.trending_down,
                  ),
                  StatCard(
                    title: "Total Jabatan",
                    value: "${stats.jabatan}",
                    color: AppColors.statYellow,
                    footer: "Jabatan terdaftar",
                    footerIcon: Icons.work_outline,
                  ),
                  StatCard(
                    title: "Total Bonus",
                    value: "${stats.bonus}",
                    color: AppColors.statBlue,
                    footer: "Bonus tercatat",
                    footerIcon: Icons.card_giftcard_outlined,
                  ),
                ],
              );
            },
          ),
          const SizedBox(height: AppSpacing.lg),
          Text(
            "Modul",
            style: Theme.of(context).textTheme.titleLarge,
          ),
          const SizedBox(height: AppSpacing.sm),
          Card(
            child: Column(
              children: [
                _ModuleTile(
                  icon: Icons.people_outline,
                  color: AppColors.statBlue,
                  title: "Karyawan",
                  subtitle: "Kelola data karyawan",
                  onTap: () => Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const EmployeePage()),
                  ),
                ),
                const Divider(height: 1, indent: 68),
                _ModuleTile(
                  icon: Icons.work_outline,
                  color: AppColors.statYellow,
                  title: "Jabatan",
                  subtitle: "Kelola jabatan & gaji pokok",
                  onTap: () => Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const JabatanPage()),
                  ),
                ),
                const Divider(height: 1, indent: 68),
                _ModuleTile(
                  icon: Icons.card_giftcard_outlined,
                  color: AppColors.statPurple,
                  title: "Bonus",
                  subtitle: "Kelola bonus karyawan",
                  onTap: () => Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const BonusPage()),
                  ),
                ),
                const Divider(height: 1, indent: 68),
                _ModuleTile(
                  icon: Icons.payments_outlined,
                  color: AppColors.statGreen,
                  title: "Payroll",
                  subtitle: "Proses pembayaran gaji",
                  onTap: () => Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const PayrollPage()),
                  ),
                ),
                const Divider(height: 1, indent: 68),
                _ModuleTile(
                  icon: Icons.person_outline,
                  color: AppColors.statTeal,
                  title: "Profil",
                  subtitle: "Lihat profil akun",
                  onTap: () => Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const ProfilePage()),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: AppSpacing.lg),
        ],
      ),
    );
  }

  // ---------------------------------------------------------------------
  // Home untuk role karyawan: sambutan + akses cepat
  // ---------------------------------------------------------------------
  Widget _buildEmployeeHome(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(AppSpacing.md),
      children: [
        Container(
          width: double.infinity,
          padding: const EdgeInsets.all(AppSpacing.lg),
          decoration: BoxDecoration(
            gradient: const LinearGradient(
              colors: [AppColors.statBlue, AppColors.primaryDark],
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
            borderRadius: BorderRadius.circular(AppRadius.md),
          ),
          child: const Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                "Selamat datang!",
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 20,
                  fontWeight: FontWeight.w800,
                ),
              ),
              SizedBox(height: 4),
              Text(
                "Kelola profil dan metode gaji Anda di sini.",
                style: TextStyle(color: Colors.white70, fontSize: 13),
              ),
            ],
          ),
        ),
        const SizedBox(height: AppSpacing.lg),
        Text("Akses Cepat", style: Theme.of(context).textTheme.titleLarge),
        const SizedBox(height: AppSpacing.sm),
        Card(
          child: Column(
            children: [
              _ModuleTile(
                icon: Icons.person_outline,
                color: AppColors.statTeal,
                title: "Profil Saya",
                subtitle: "Lihat data diri Anda",
                onTap: () => Navigator.push(
                  context,
                  MaterialPageRoute(builder: (_) => const ProfilePage()),
                ),
              ),
              const Divider(height: 1, indent: 68),
              _ModuleTile(
                icon: Icons.account_balance_wallet_outlined,
                color: AppColors.statGreen,
                title: "Metode Gaji",
                subtitle: "Atur metode pembayaran",
                onTap: () => Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (_) => const EmployeePayrollPage(),
                  ),
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}

class _ModuleTile extends StatelessWidget {
  final IconData icon;
  final Color color;
  final String title;
  final String subtitle;
  final VoidCallback onTap;

  const _ModuleTile({
    required this.icon,
    required this.color,
    required this.title,
    required this.subtitle,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return ListTile(
      onTap: onTap,
      leading: IconAvatar(icon: icon, color: color),
      title: Text(title, style: Theme.of(context).textTheme.titleMedium),
      subtitle: Text(subtitle, style: Theme.of(context).textTheme.bodyMedium),
      trailing: const Icon(Icons.chevron_right, color: AppColors.textDisabled),
    );
  }
}