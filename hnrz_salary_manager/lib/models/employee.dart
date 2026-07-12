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
      jabatan: json['jabatan'] ?? '',
      jabatanId: json['jabatan_id'],
      role: json['role'] ?? '',
      isActive: json['is_active'] ?? false,
    );
  }
}