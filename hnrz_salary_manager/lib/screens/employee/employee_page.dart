import 'package:flutter/material.dart';

import '../../models/employee.dart';
import '../../services/employee_service.dart';

class EmployeePage extends StatefulWidget {
  const EmployeePage({super.key});

  @override
  State<EmployeePage> createState() => _EmployeePageState();
}

class _EmployeePageState extends State<EmployeePage> {
  final _service = EmployeeService();
  late Future<Employee> _profile;

  @override
  void initState() {
    super.initState();
    _profile = _service.getMyProfile();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('My Profile')),
      body: FutureBuilder<Employee>(
        future: _profile,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }

          if (snapshot.hasError) {
            return Center(
              child: Padding(
                padding: const EdgeInsets.all(24),
                child: Text(snapshot.error.toString()),
              ),
            );
          }

          final employee = snapshot.data!;
          return ListView(
            padding: const EdgeInsets.all(16),
            children: [
              _ProfileItem(label: 'Name', value: employee.name),
              _ProfileItem(label: 'Email', value: employee.email),
              if (employee.isEmployee) ...[
                _ProfileItem(label: 'Employee ID', value: employee.employeeId),
                _ProfileItem(label: 'Phone', value: employee.phone),
                _ProfileItem(label: 'Address', value: employee.address),
                _ProfileItem(label: 'Position', value: employee.position),
                _ProfileItem(
                  label: 'Base Salary',
                  value: _formatRupiah(employee.baseSalary),
                ),
                _ProfileItem(label: 'Payroll Method', value: employee.payrollMethod),
              ],
            ],
          );
        },
      ),
    );
  }

  String _formatRupiah(int amount) {
    final digits = amount.toString();
    final formatted = digits.replaceAllMapped(
      RegExp(r'(?<!^)(?=(\d{3})+$)'),
      (match) => '.',
    );
    return 'Rp $formatted';
  }
}

class _ProfileItem extends StatelessWidget {
  final String label;
  final String value;

  const _ProfileItem({required this.label, required this.value});

  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(title: Text(label), subtitle: Text(value)),
    );
  }
}
