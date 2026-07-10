import 'package:flutter/material.dart';

import '../auth/login_page.dart';
import '../jabatan/jabatan_page.dart';
import '../bonus/bonus_page.dart';
import '../../services/auth_service.dart';

class DashboardPage extends StatelessWidget {
  const DashboardPage({super.key});

  @override
  Widget build(BuildContext context) {
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
          children: [

            DashboardCard(
              title: "Employee",
              icon: Icons.people,
              onTap: () {},
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
              onTap: () {},
            ),

            DashboardCard(
              title: "Profile",
              icon: Icons.person,
              onTap: () {},
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

            Icon(
              icon,
              size: 55,
            ),

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