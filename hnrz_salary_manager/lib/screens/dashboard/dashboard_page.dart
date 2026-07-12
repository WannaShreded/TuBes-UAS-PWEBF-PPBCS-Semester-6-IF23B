import 'package:flutter/material.dart';

import '../auth/login_page.dart';
import '../jabatan/jabatan_page.dart';
import '../bonus/bonus_page.dart';
import '../../services/auth_service.dart';
import '../payroll_method/payroll_page.dart';
import '../employee/employee_page.dart';
import '../employee/profile_page.dart';
import '../payroll_method/employee_payroll_page.dart';

class DashboardPage extends StatelessWidget {
  final List<String> roles;

  const DashboardPage({super.key, this.roles = const []});

  @override
  Widget build(BuildContext context) {
    final isEmployeeOnly = roles.contains('karyawan') && !roles.contains('admin');

    return Scaffold(
      appBar: AppBar(
        title: const Text("HNRZ Salary Manager"),
        centerTitle: true,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: GridView.count(
          crossAxisCount: 2,
          crossAxisSpacing: 15,
          mainAxisSpacing: 15,
          children: isEmployeeOnly
              ? [
                  DashboardCard(
                    title: "My Profile",
                    icon: Icons.person,
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => const ProfilePage(),
                        ),
                      );
                    },
                  ),
                  DashboardCard(
                    title: "Payroll Method",
                    icon: Icons.account_balance_wallet,
                    onTap: () {
                      Navigator.push(context, MaterialPageRoute(builder: (_) => const EmployeePayrollPage()));
                    },
                  ),
                  DashboardCard(
                    title: "Logout",
                    icon: Icons.logout,
                    onTap: () async {
                      await AuthService().logout();

                      if (!context.mounted) return;

                      Navigator.pushAndRemoveUntil(
                        context,
                        MaterialPageRoute(builder: (_) => const LoginPage()),
                        (route) => false,
                      );
                    },
                  ),
                ]
              : [

            DashboardCard(
              title: "Employee",
              icon: Icons.people,
              onTap: () {
                Navigator.push(context, MaterialPageRoute(builder: (_) => const EmployeePage()));
              },
            ),

            DashboardCard(
              title: "Jabatan",
              icon: Icons.work,
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (_) => const JabatanPage(),
                  ),
                );
              },
            ),

            DashboardCard(
              title: "Bonus",
              icon: Icons.card_giftcard,
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (_) => const BonusPage(),
                  ),
                );
              },
            ),

            DashboardCard(
              title: "Payroll",
              icon: Icons.payments,
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (_) => const PayrollPage(),
                  ),
                );
              },
            ),

            DashboardCard(
              title: "Profile",
              icon: Icons.person,
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (_) => const ProfilePage()),
                );
              },
            ),

            DashboardCard(
              title: "Logout",
              icon: Icons.logout,
              onTap: () async {
                await AuthService().logout();

                if (!context.mounted) return;

                Navigator.pushAndRemoveUntil(
                  context,
                  MaterialPageRoute(
                    builder: (_) => const LoginPage(),
                  ),
                  (route) => false,
                );
              },
            ),

                ],
        ),
      ),
    );
  }
}

class DashboardCard extends StatelessWidget {
  final String title;
  final IconData icon;
  final VoidCallback onTap;

  const DashboardCard({
    super.key,
    required this.title,
    required this.icon,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 4,
      child: InkWell(
        borderRadius: BorderRadius.circular(12),
        onTap: onTap,
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 55),
            const SizedBox(height: 15),
            Text(
              title,
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
