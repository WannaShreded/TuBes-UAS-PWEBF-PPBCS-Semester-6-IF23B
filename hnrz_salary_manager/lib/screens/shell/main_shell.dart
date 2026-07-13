import 'package:flutter/material.dart';

import '../auth/login_page.dart';
import '../dashboard/dashboard_page.dart';
import '../jabatan/jabatan_page.dart';
import '../bonus/bonus_page.dart';
import '../employee/employee_page.dart';
import '../employee/profile_page.dart';
import '../payroll_method/payroll_page.dart';
import '../payroll_method/employee_payroll_page.dart';
import '../../services/auth_service.dart';
import '../../theme/app_theme.dart';

/// Shell dengan bottom navigation bar, menggantikan pola AppBar+grid menu.
/// Menu yang ditampilkan menyesuaikan role (admin vs karyawan biasa).
class MainShell extends StatefulWidget {
  final List<String> roles;

  const MainShell({super.key, this.roles = const []});

  @override
  State<MainShell> createState() => _MainShellState();
}

class _MainShellState extends State<MainShell> {
  int _index = 0;

  bool get _isEmployeeOnly =>
      widget.roles.contains('karyawan') && !widget.roles.contains('admin');

  late final List<Widget> _adminPages = [
    DashboardPage(roles: widget.roles),
    const EmployeePage(),
    const JabatanPage(),
    const BonusPage(),
    const PayrollPage(),
  ];

  late final List<Widget> _employeePages = [
    DashboardPage(roles: widget.roles),
    const EmployeePayrollPage(),
    const ProfilePage(),
  ];

  static const _adminItems = [
    BottomNavigationBarItem(icon: Icon(Icons.home_outlined), activeIcon: Icon(Icons.home), label: "Home"),
    BottomNavigationBarItem(icon: Icon(Icons.people_outline), activeIcon: Icon(Icons.people), label: "Karyawan"),
    BottomNavigationBarItem(icon: Icon(Icons.work_outline), activeIcon: Icon(Icons.work), label: "Jabatan"),
    BottomNavigationBarItem(icon: Icon(Icons.card_giftcard_outlined), activeIcon: Icon(Icons.card_giftcard), label: "Bonus"),
    BottomNavigationBarItem(icon: Icon(Icons.payments_outlined), activeIcon: Icon(Icons.payments), label: "Payroll"),
  ];

  static const _employeeItems = [
    BottomNavigationBarItem(icon: Icon(Icons.home_outlined), activeIcon: Icon(Icons.home), label: "Home"),
    BottomNavigationBarItem(icon: Icon(Icons.account_balance_wallet_outlined), activeIcon: Icon(Icons.account_balance_wallet), label: "Payroll"),
    BottomNavigationBarItem(icon: Icon(Icons.person_outline), activeIcon: Icon(Icons.person), label: "Profil"),
  ];

  Future<void> _logout() async {
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

    if (!mounted) return;

    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(builder: (_) => const LoginPage()),
      (route) => false,
    );
  }

  @override
  Widget build(BuildContext context) {
    final pages = _isEmployeeOnly ? _employeePages : _adminPages;
    final items = _isEmployeeOnly ? _employeeItems : _adminItems;

    return Scaffold(
      body: Stack(
        children: [
          IndexedStack(index: _index, children: pages),

          // Tombol logout mengambang di pojok kanan atas,
          // agar tetap mudah diakses dari tab manapun.
          Positioned(
            top: MediaQuery.of(context).padding.top + 8,
            right: AppSpacing.md,
            child: Material(
              color: Colors.transparent,
              child: InkWell(
                borderRadius: BorderRadius.circular(20),
                onTap: _logout,
                child: Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: AppColors.surface,
                    shape: BoxShape.circle,
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withValues(alpha: 0.06),
                        blurRadius: 8,
                      ),
                    ],
                  ),
                  child: const Icon(
                    Icons.logout,
                    size: 18,
                    color: AppColors.danger,
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _index,
        onTap: (value) => setState(() => _index = value),
        items: items,
      ),
    );
  }
}