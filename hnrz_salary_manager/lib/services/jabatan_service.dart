import 'package:http/http.dart' as http;
import 'dart:convert';

import '../models/jabatan.dart';
import 'api_client.dart';
import 'auth_service.dart';

class JabatanService {
  Future<List<Jabatan>> getAll() async {
    final token = await AuthService().getToken();

    final response = await http.get(
      Uri.parse("${ApiClient.baseUrl}/jabatan"),
      headers: {"Accept": "application/json", "Authorization": "Bearer $token"},
    );

    print(response.statusCode);
    print(response.body);

    if (response.statusCode == 200) {
      final body = jsonDecode(response.body);

      return (body["data"] as List).map((e) => Jabatan.fromJson(e)).toList();
    }

    throw Exception(response.body);
  }

  Future<bool> create({
    required String name,
    required int salary,
    required String description,
  }) async {
    final token = await AuthService().getToken();
    final response = await http.post(
      Uri.parse("${ApiClient.baseUrl}/jabatan"),
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "Authorization": "Bearer $token",
      },
      body: jsonEncode({
        "name": name,
        "salary": salary,
        "description": description,
      }),
    );

    return response.statusCode == 201;
  }

  Future<bool> update({
    required int id,
    required String name,
    required int salary,
    required String description,
  }) async {
    final token = await AuthService().getToken();
    final response = await http.put(
      Uri.parse("${ApiClient.baseUrl}/jabatan/$id"),
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "Authorization": "Bearer $token",
      },
      body: jsonEncode({
        "name": name,
        "salary": salary,
        "description": description,
      }),
    );

    return response.statusCode == 200;
  }

  Future<bool> delete(int id) async {
    final token = await AuthService().getToken();
    final response = await http.delete(
      Uri.parse("${ApiClient.baseUrl}/jabatan/$id"),
      headers: {
        "Accept": "application/json",
        "Authorization": "Bearer $token",
      },
    );

    print("Status Code: ${response.statusCode}");
    print("Response: ${response.body}");

    return response.statusCode == 200;
  }
}
