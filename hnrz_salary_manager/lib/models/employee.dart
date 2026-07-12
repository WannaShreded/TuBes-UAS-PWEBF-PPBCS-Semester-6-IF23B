class Employee {
  final int id;
  final String idPekerja;
  final String nik;
  final String namaLengkap;
  final String noTelepon;
  final String email;
  final String alamat;
  final String jabatan;
  final int? jabatanId;
  final String role;
   final bool isActive;
   final String payrollMethodName;
   final int baseSalary;
   final bool isEmployee;

  Employee({
    required this.id,
    required this.idPekerja,
    required this.nik,
    required this.namaLengkap,
    required this.noTelepon,
    required this.email,
    required this.alamat,
    required this.jabatan,
    this.jabatanId,
    required this.role,
     required this.isActive,
     this.payrollMethodName = '-',
     this.baseSalary = 0,
     this.isEmployee = false,
  });

  factory Employee.fromJson(Map<String, dynamic> json) {
    return Employee(
      id: json['id'],
      idPekerja: json['id_pekerja'] ?? '',
      nik: json['nik'] ?? '',
      namaLengkap: json['nama_lengkap'] ?? '',
      noTelepon: json['no_telepon'] ?? '',
      email: json['email'] ?? '',
      alamat: json['alamat'] ?? '',
       jabatan: (json['position']?['name'] ?? json['jabatan'] ?? '').toString(),
      jabatanId: json['jabatan_id'],
      role: json['role'] ?? '',
       isActive: json['is_active'] ?? false,
       payrollMethodName: (json['payroll_method']?['name'] ?? '-').toString(),
       baseSalary: (json['position']?['salary'] as num?)?.toInt() ?? 0,
       isEmployee: json['is_employee'] == true,
    );
  }
}
