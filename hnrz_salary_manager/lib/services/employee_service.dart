import 'dart:convert';

import 'package:http/http.dart' as http;

import '../models/employee.dart';
import 'api_client.dart';
import 'auth_service.dart';

class EmployeeService {
  Future<Employee> getMyProfile() async {
    final token = await AuthService().getToken();
    final response = await http.get(
      Uri.parse('${ApiClient.baseUrl}/profile'),
      headers: {
        'Accept': 'application/json',
        if (token != null && token.isNotEmpty) 'Authorization': 'Bearer $token',
      },
    );

    final body = jsonDecode(response.body) as Map<String, dynamic>;
    if (response.statusCode == 200 && body['data'] is Map<String, dynamic>) {
      return Employee.fromJson(body['data'] as Map<String, dynamic>);
    }

    throw Exception(body['message'] ?? 'Gagal mengambil profil karyawan.');
  }
}
