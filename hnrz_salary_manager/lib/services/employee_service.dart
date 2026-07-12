import 'dart:convert';
import 'package:http/http.dart' as http;

import '../models/employee.dart';
import 'api_client.dart';
import 'auth_service.dart';

class EmployeeService {
  Future<Employee> getMyProfile() async {
    final token = await AuthService().getToken();
    final response = await http.get(Uri.parse('${ApiClient.baseUrl}/profile'), headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'});
    final body = jsonDecode(response.body) as Map<String, dynamic>;
    if (response.statusCode == 200) return Employee.fromJson(body['data'] as Map<String, dynamic>);
    throw Exception(body['message'] ?? 'Gagal mengambil profil.');
  }
  Future<List<Employee>> getAll() async {
    final token = await AuthService().getToken();

    final response = await http.get(
      Uri.parse("${ApiClient.baseUrl}/employee"),
      headers: {"Accept": "application/json", "Authorization": "Bearer $token"},
    );

    if (response.statusCode == 200) {
      final body = jsonDecode(response.body);

      return (body["data"] as List).map((e) => Employee.fromJson(e)).toList();
    }

    throw Exception(response.body);
  }

  Future<bool> create({
    required String nik,
    required String namaLengkap,
    required String noTelepon,
    required String email,
    required String alamat,
    required String jabatan,
    required String role,
    required String password,
    required bool isActive,
  }) async {
    try {
      final token = await AuthService().getToken();

      final response = await http.post(
        Uri.parse("${ApiClient.baseUrl}/employee"),
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "Authorization": "Bearer $token",
        },
        body: jsonEncode({
          "nik": nik,
          "nama_lengkap": namaLengkap,
          "no_telepon": noTelepon,
          "email": email,
          "alamat": alamat,
          "jabatan": jabatan,
          "role": role,
          "password": password,
          "is_active": isActive,
        }),
      );

      print("CREATE STATUS : ${response.statusCode}");
      print("CREATE BODY : ${response.body}");

      return response.statusCode == 201;
    } catch (e) {
      print(e);
      return false;
    }
  }

  Future<bool> update({
    required int id,
    required String nik,
    required String namaLengkap,
    required String noTelepon,
    required String email,
    required String alamat,
    required String jabatan,
    required String role,
    required bool isActive,
  }) async {
    try {
      final token = await AuthService().getToken();

      final response = await http.put(
        Uri.parse("${ApiClient.baseUrl}/employee/$id"),
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "Authorization": "Bearer $token",
        },
        body: jsonEncode({
          "nik": nik,
          "nama_lengkap": namaLengkap,
          "no_telepon": noTelepon,
          "email": email,
          "alamat": alamat,
          "jabatan": jabatan,
          "role": role,
          "is_active": isActive,
        }),
      );

      print("UPDATE STATUS : ${response.statusCode}");
      print("UPDATE BODY : ${response.body}");

      return response.statusCode == 200;
    } catch (e) {
      print(e);
      return false;
    }
  }

  Future<bool> delete(int id) async {
    try {
      final token = await AuthService().getToken();

      final response = await http.delete(
        Uri.parse("${ApiClient.baseUrl}/employee/$id"),
        headers: {
          "Accept": "application/json",
          "Authorization": "Bearer $token",
        },
      );

      print("DELETE STATUS : ${response.statusCode}");
      print("DELETE BODY : ${response.body}");

      return response.statusCode == 200 || response.statusCode == 204;
    } catch (e) {
      print(e);
      return false;
    }
  }
}
