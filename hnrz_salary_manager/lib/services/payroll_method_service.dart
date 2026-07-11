import 'package:http/http.dart' as http;
import 'dart:convert';

import '../models/payroll_method.dart';
import 'api_client.dart';
import 'auth_service.dart';

class PayrollMethodService {
  Future<List<PayrollMethod>> getAll() async {
    final token = await AuthService().getToken();

    final response = await http.get(
      Uri.parse("${ApiClient.baseUrl}/payroll-methods"),
      headers: {"Accept": "application/json", "Authorization": "Bearer $token"},
    );

    if (response.statusCode == 200) {
      final body = jsonDecode(response.body);
      return (body["data"] as List)
          .map((e) => PayrollMethod.fromJson(e))
          .toList();
    }

    throw Exception(response.body);
  }

  Future<bool> create({
    required String type,
    required String name,
    String? description,
    bool isActive = true,
  }) async {
    final token = await AuthService().getToken();
    final response = await http.post(
      Uri.parse("${ApiClient.baseUrl}/payroll-methods"),
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "Authorization": "Bearer $token",
      },
      body: jsonEncode({
        "type": type,
        "name": name,
        "description": description,
        "is_active": isActive,
      }),
    );

    return response.statusCode == 201;
  }

  Future<bool> update({
    required int id,
    required String type,
    required String name,
    String? description,
    bool isActive = true,
  }) async {
    final token = await AuthService().getToken();
    final response = await http.put(
      Uri.parse("${ApiClient.baseUrl}/payroll-methods/$id"),
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "Authorization": "Bearer $token",
      },
      body: jsonEncode({
        "type": type,
        "name": name,
        "description": description,
        "is_active": isActive,
      }),
    );

    return response.statusCode == 200;
  }

  Future<bool> delete(int id) async {
    final token = await AuthService().getToken();
    final response = await http.delete(
      Uri.parse("${ApiClient.baseUrl}/payroll-methods/$id"),
      headers: {
        "Accept": "application/json",
        "Authorization": "Bearer $token",
      },
    );

    return response.statusCode == 200;
  }
}