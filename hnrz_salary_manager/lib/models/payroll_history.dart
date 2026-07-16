class PayrollHistory {
  final int id;
  final int employeeId;
  final int? bonusId;
  final int? paymentMethodId;
  final String jabatan;
  final int gajiPokok;
  final int bonus;
  final int totalDibayarkan;
  final String paymentMethod;
  final String paymentStatus;
  final String payrollPeriod;
  final String? paymentDate;
  final String? notes;

  PayrollHistory({
    required this.id,
    required this.employeeId,
    this.bonusId,
    this.paymentMethodId,
    required this.jabatan,
    required this.gajiPokok,
    required this.bonus,
    required this.totalDibayarkan,
    required this.paymentMethod,
    required this.paymentStatus,
    required this.payrollPeriod,
    this.paymentDate,
    this.notes,
  });

  factory PayrollHistory.fromJson(Map<String, dynamic> json) {
    return PayrollHistory(
      id: json['id'] as int,
      employeeId: json['employee_id'] as int,
      bonusId: json['bonus_id'] as int?,
      paymentMethodId: json['payment_method_id'] as int?,
      jabatan: json['jabatan'] as String,
      gajiPokok: json['gaji_pokok'] as int,
      bonus: json['bonus'] as int,
      totalDibayarkan: json['total_dibayarkan'] as int,
      paymentMethod: json['payment_method'] as String,
      paymentStatus: json['payment_status'] as String,
      payrollPeriod: json['payroll_period'] as String,
      paymentDate: json['payment_date'] as String?,
      notes: json['notes'] as String?,
    );
  }
}
