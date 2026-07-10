import 'dart:convert';

import 'package:http/http.dart' as http;

import '../models/bonus.dart';
import 'api_client.dart';
import 'auth_service.dart';

class BonusService {
  Future<List<Bonus>> getAll() async {
    final token = await AuthService().getToken();

    final response = await http.get(
      Uri.parse("${ApiClient.baseUrl}/bonus"),
      headers: {
        "Accept": "application/json",
        "Authorization": "Bearer $token",
      },
    );

    if (response.statusCode == 200) {
      final body = jsonDecode(response.body);

      return (body["data"] as List)
          .map((e) => Bonus.fromJson(e))
          .toList();
    }

    throw Exception(response.body);
  }

  Future<bool> create({
    required String namaBonus,
    required double nominalBonus,
    required String jenisBonus,
    required String periodeBonus,
    required String keterangan,
  }) async {
    try {
      final token = await AuthService().getToken();
      final response = await http.post(
        Uri.parse("${ApiClient.baseUrl}/bonus"),
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "Authorization": "Bearer $token",
        },
        body: jsonEncode({
          "nama_bonus": namaBonus,
          "nominal_bonus": nominalBonus,
          "jenis_bonus": jenisBonus,
          "periode_bonus": periodeBonus,
          "keterangan": keterangan,
        }),
      );

      print("Create Status Code: ${response.statusCode}");
      print("Create Response: ${response.body}");

      return response.statusCode == 201 || response.statusCode == 200;
    } catch (e) {
      print("Create Error: $e");
      return false;
    }
  }

  Future<bool> update({
    required int id,
    required String namaBonus,
    required double nominalBonus,
    required String jenisBonus,
    required String periodeBonus,
    required String keterangan,
  }) async {
    try {
      final token = await AuthService().getToken();
      print("UPDATE URL: ${ApiClient.baseUrl}/bonus/$id");
      final response = await http.put(
        Uri.parse("${ApiClient.baseUrl}/bonus/$id"),
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "Authorization": "Bearer $token",
        },
        body: jsonEncode({
          "nama_bonus": namaBonus,
          "nominal_bonus": nominalBonus,
          "jenis_bonus": jenisBonus,
          "periode_bonus": periodeBonus,
          "keterangan": keterangan,
        }),
      );

      print("Update Status Code: ${response.statusCode}");
      print("Update Response: ${response.body}");

      return response.statusCode == 200 || response.statusCode == 201;
    } catch (e) {
      print("Update Error: $e");
      return false;
    }
  }

  Future<bool> delete(int id) async {
    try {
      final token = await AuthService().getToken();
      print("DELETE URL: ${ApiClient.baseUrl}/bonus/$id");
      final response = await http.delete(
        Uri.parse("${ApiClient.baseUrl}/bonus/$id"),
        headers: {
          "Accept": "application/json",
          "Authorization": "Bearer $token",
        },
      );

      print("Delete Status Code: ${response.statusCode}");
      print("Delete Response: ${response.body}");

      return response.statusCode == 200 || response.statusCode == 204;
    } catch (e) {
      print("Delete Error: $e");
      return false;
    }
  }
}