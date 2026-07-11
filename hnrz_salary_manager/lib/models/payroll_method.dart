class PayrollMethod {
  final int id;
  final String type;
  final String name;
  final String? description;
  final bool isActive;

  PayrollMethod({
    required this.id,
    required this.type,
    required this.name,
    this.description,
    required this.isActive,
  });

  factory PayrollMethod.fromJson(Map<String, dynamic> json) {
    return PayrollMethod(
      id: json['id'],
      type: json['type'],
      name: json['name'],
      description: json['description'],
      isActive: json['is_active'] == true || json['is_active'] == 1,
    );
  }
}