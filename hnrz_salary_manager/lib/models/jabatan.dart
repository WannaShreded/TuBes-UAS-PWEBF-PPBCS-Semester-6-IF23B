class Jabatan {
  final int id;
  final String name;
  final int salary;
  final String description;

  Jabatan({
    required this.id,
    required this.name,
    required this.salary,
    required this.description,
  });

  factory Jabatan.fromJson(Map<String, dynamic> json) {
    return Jabatan(
      id: json['id'],
      name: json['name'],
      salary: json['salary'],
      description: json['description'],
    );
  }
}