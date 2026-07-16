import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/payroll_history.dart';
import 'api_client.dart';
import 'auth_service.dart';

class EmployeePayrollHistoryResponse {
  final int totalPayroll;
  final int averagePayroll;
  final List<PayrollHistory> histories;

  EmployeePayrollHistoryResponse({
    required this.totalPayroll,
    required this.averagePayroll,
    required this.histories,
  });
}

class PayrollHistoryService {
  Future<EmployeePayrollHistoryResponse> getMyPayrollHistory() async {
    final token = await AuthService().getToken();
    final response = await http.get(
      Uri.parse('${ApiClient.baseUrl}/employee/payroll-history'),
      headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    final body = jsonDecode(response.body);

    if (response.statusCode == 200) {
      final data = body['data'] as Map<String, dynamic>;
      final list = data['histories'] as List;
      final histories = list.map((e) => PayrollHistory.fromJson(e)).toList();

      return EmployeePayrollHistoryResponse(
        totalPayroll: data['total_payroll'] as int,
        averagePayroll: data['average_payroll'] as int,
        histories: histories,
      );
    }

    throw Exception(body['message'] ?? 'Gagal mengambil riwayat gaji.');
  }
}
