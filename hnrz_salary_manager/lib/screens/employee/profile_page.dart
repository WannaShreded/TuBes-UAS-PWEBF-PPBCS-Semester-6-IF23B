import 'package:flutter/material.dart';

import '../../models/employee.dart';
import '../../services/employee_service.dart';
import 'change_password_page.dart';

class ProfilePage extends StatefulWidget {
  const ProfilePage({super.key});
  @override
  State<ProfilePage> createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage> {
  final _service = EmployeeService();
  late Future<Employee> _profile;
  @override
  void initState() { super.initState(); _profile = _service.getMyProfile(); }
  @override
  Widget build(BuildContext context) => Scaffold(
    appBar: AppBar(title: const Text('My Profile')),
    body: FutureBuilder<Employee>(future: _profile, builder: (context, snapshot) {
      if (snapshot.connectionState == ConnectionState.waiting) return const Center(child: CircularProgressIndicator());
      if (snapshot.hasError) return Center(child: Text(snapshot.error.toString()));
      final employee = snapshot.data!;
      return ListView(padding: const EdgeInsets.all(16), children: [
        _Item('Name', employee.namaLengkap), _Item('Email', employee.email),
        OutlinedButton.icon(onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ChangePasswordPage())), icon: const Icon(Icons.lock_outline), label: const Text('Change Password')),
        if (employee.isEmployee) ...[_Item('Employee ID', employee.idPekerja), _Item('Position', employee.jabatan), _Item('Base Salary', 'Rp ${employee.baseSalary}'), _Item('Payroll Method', employee.payrollMethodName)],
      ]);
    }),
  );
}
class _Item extends StatelessWidget { final String label; final String value; const _Item(this.label, this.value); @override Widget build(BuildContext context) => Card(child: ListTile(title: Text(label), subtitle: Text(value))); }
