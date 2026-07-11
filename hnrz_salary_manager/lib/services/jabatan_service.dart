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
      headers: {
        "Accept": "application/json",
        if (token != null && token.isNotEmpty) "Authorization": "Bearer $token",
      },
    );

    if (response.statusCode == 200) {
      final body = jsonDecode(response.body);
      if (body is! Map<String, dynamic> || body['data'] is! List) {
        throw Exception('Format respons tidak valid');
      }

      return (body['data'] as List)
          .map((e) => Jabatan.fromJson(e as Map<String, dynamic>))
          .toList();
    }

    throw Exception(
      'Gagal mengambil data jabatan: ${response.statusCode} ${response.body}',
    );
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
        if (token != null && token.isNotEmpty) "Authorization": "Bearer $token",
      },
      body: jsonEncode({
        "name": name,
        "salary": salary,
        "description": description,
      }),
    );

    if (response.statusCode != 201) {
      throw Exception(
        'Gagal membuat jabatan: ${response.statusCode} ${response.body}',
      );
    }

    return true;
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
        if (token != null && token.isNotEmpty) "Authorization": "Bearer $token",
      },
      body: jsonEncode({
        "name": name,
        "salary": salary,
        "description": description,
      }),
    );

    if (response.statusCode != 200) {
      throw Exception(
        'Gagal memperbarui jabatan: ${response.statusCode} ${response.body}',
      );
    }

    return true;
  }

  Future<bool> delete(int id) async {
    final token = await AuthService().getToken();
    final response = await http.delete(
      Uri.parse("${ApiClient.baseUrl}/jabatan/$id"),
      headers: {
        "Accept": "application/json",
        if (token != null && token.isNotEmpty) "Authorization": "Bearer $token",
      },
    );

    if (response.statusCode != 200) {
      throw Exception(
        'Gagal menghapus jabatan: ${response.statusCode} ${response.body}',
      );
    }

    return true;
  }
}
