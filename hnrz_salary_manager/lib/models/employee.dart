class Employee {
  final String employeeId;
  final String name;
  final String email;
  final String phone;
  final String address;
  final String position;
  final String payrollMethod;
  final bool isEmployee;
  final int baseSalary;

  const Employee({
    required this.employeeId,
    required this.name,
    required this.email,
    required this.phone,
    required this.address,
    required this.position,
    required this.payrollMethod,
    required this.isEmployee,
    required this.baseSalary,
  });

  factory Employee.fromJson(Map<String, dynamic> json) {
    final position = json['position'] as Map<String, dynamic>?;
    final payrollMethod = json['payroll_method'] as Map<String, dynamic>?;

    return Employee(
      employeeId: json['id_pekerja']?.toString() ?? '-',
      name: json['nama_lengkap']?.toString() ?? '-',
      email: json['email']?.toString() ?? '-',
      phone: json['no_telepon']?.toString() ?? '-',
      address: json['alamat']?.toString() ?? '-',
      position: position?['name']?.toString() ?? json['jabatan']?.toString() ?? '-',
      payrollMethod: payrollMethod?['name']?.toString() ?? '-',
      isEmployee: json['is_employee'] == true,
      baseSalary: (position?['salary'] as num?)?.toInt() ?? 0,
    );
  }
}
