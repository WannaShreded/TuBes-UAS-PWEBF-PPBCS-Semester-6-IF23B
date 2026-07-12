import 'package:flutter/material.dart';

import '../auth/login_page.dart';
import '../jabatan/jabatan_page.dart';
import '../bonus/bonus_page.dart';
import '../../services/auth_service.dart';
import '../payroll_method/payroll_page.dart';
import '../employee/employee_page.dart';
import '../employee/profile_page.dart';
import '../payroll_method/employee_payroll_page.dart';
import '../../theme/app_theme.dart';

class DashboardPage extends StatelessWidget {
  final List<String> roles;

  const DashboardPage({super.key, this.roles = const []});

  Future<void> _logout(BuildContext context) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(AppRadius.md),
        ),
        title: const Text("Keluar"),
        content: const Text("Apakah Anda yakin ingin keluar?"),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text("Batal"),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.danger),
            onPressed: () => Navigator.pop(context, true),
            child: const Text("Keluar"),
          ),
        ],
      ),
    );

    if (confirm != true) return;

    await AuthService().logout();

    if (!context.mounted) return;

    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(builder: (_) => const LoginPage()),
      (route) => false,
    );
  }

  @override
  Widget build(BuildContext context) {
    final isEmployeeOnly =
        roles.contains('karyawan') && !roles.contains('admin');

    final menuItems = isEmployeeOnly
        ? [
            _MenuItem(
              title: "Profil Saya",
              subtitle: "Lihat data diri Anda",
              icon: Icons.person_outline,
              onTap: () => Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const ProfilePage()),
              ),
            ),
            _MenuItem(
              title: "Metode Gaji",
              subtitle: "Atur metode pembayaran",
              icon: Icons.account_balance_wallet_outlined,
              onTap: () => Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => const EmployeePayrollPage(),
                ),
              ),
            ),
          ]
        : [
            _MenuItem(
              title: "Karyawan",
              subtitle: "Kelola data karyawan",
              icon: Icons.people_outline,
              onTap: () => Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const EmployeePage()),
              ),
            ),
            _MenuItem(
              title: "Jabatan",
              subtitle: "Kelola jabatan & gaji pokok",
              icon: Icons.work_outline,
              onTap: () => Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const JabatanPage()),
              ),
            ),
            _MenuItem(
              title: "Bonus",
              subtitle: "Kelola bonus karyawan",
              icon: Icons.card_giftcard_outlined,
              onTap: () => Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const BonusPage()),
              ),
            ),
            _MenuItem(
              title: "Payroll",
              subtitle: "Proses pembayaran gaji",
              icon: Icons.payments_outlined,
              onTap: () => Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const PayrollPage()),
              ),
            ),
            _MenuItem(
              title: "Profil",
              subtitle: "Lihat profil akun",
              icon: Icons.person_outline,
              onTap: () => Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const ProfilePage()),
              ),
            ),
          ];

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text("HNRZ Salary Manager"),
        actions: [
          IconButton(
            tooltip: "Keluar",
            icon: const Icon(Icons.logout),
            onPressed: () => _logout(context),
          ),
          const SizedBox(width: AppSpacing.sm),
        ],
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(AppSpacing.md),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // ---------- Header sambutan ----------
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(AppSpacing.lg),
                  child: Row(
                    children: [
                      Container(
                        width: 48,
                        height: 48,
                        decoration: BoxDecoration(
                          color: AppColors.primary,
                          borderRadius: BorderRadius.circular(AppRadius.md),
                        ),
                        child: const Icon(
                          Icons.dashboard_outlined,
                          color: Colors.white,
                        ),
                      ),
                      const SizedBox(width: AppSpacing.md),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              "Selamat datang",
                              style: Theme.of(context).textTheme.titleMedium,
                            ),
                            const SizedBox(height: 2),
                            Text(
                              isEmployeeOnly
                                  ? "Kelola profil dan metode gaji Anda"
                                  : "Kelola data karyawan, jabatan, bonus, dan gaji",
                              style: Theme.of(context).textTheme.bodyMedium,
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: AppSpacing.lg),

              Text(
                "MENU",
                style: TextStyle(
                  fontSize: 12,
                  fontWeight: FontWeight.w700,
                  letterSpacing: 0.6,
                  color: AppColors.textSecondary,
                ),
              ),
              const SizedBox(height: AppSpacing.sm),

              // ---------- Grid menu ----------
              GridView.count(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                crossAxisCount: 2,
                crossAxisSpacing: AppSpacing.md,
                mainAxisSpacing: AppSpacing.md,
                childAspectRatio: 1.05,
                children: [
                  for (final item in menuItems) DashboardCard(item: item),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _MenuItem {
  final String title;
  final String subtitle;
  final IconData icon;
  final VoidCallback onTap;

  const _MenuItem({
    required this.title,
    required this.subtitle,
    required this.icon,
    required this.onTap,
  });
}

class DashboardCard extends StatelessWidget {
  final _MenuItem item;

  const DashboardCard({super.key, required this.item});

  @override
  Widget build(BuildContext context) {
    return Card(
      child: InkWell(
        borderRadius: BorderRadius.circular(AppRadius.md),
        onTap: item.onTap,
        child: Padding(
          padding: const EdgeInsets.all(AppSpacing.md),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                width: 44,
                height: 44,
                decoration: BoxDecoration(
                  color: AppColors.infoBg,
                  borderRadius: BorderRadius.circular(AppRadius.sm),
                ),
                child: Icon(item.icon, color: AppColors.primary, size: 24),
              ),
              const Spacer(),
              Text(
                item.title,
                style: Theme.of(context).textTheme.titleMedium,
              ),
              const SizedBox(height: 2),
              Text(
                item.subtitle,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                style: Theme.of(context).textTheme.bodyMedium,
              ),
            ],
          ),
        ),
      ),
    );
  }
}