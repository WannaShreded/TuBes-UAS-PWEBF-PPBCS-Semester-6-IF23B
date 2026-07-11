import 'dart:convert';

import 'package:http/http.dart' as http;

import '../models/jabatan.dart';

class ApiService {
  // Android Emulator
  static const String baseUrl = "http://127.0.0.1:8000/api";

  Future<List<Jabatan>> getJabatan() async {
    final response = await http.get(Uri.parse("$baseUrl/jabatan"));

    if (response.statusCode == 200) {
      final body = jsonDecode(response.body);

      List data = body["data"];

      return data.map((e) => Jabatan.fromJson(e)).toList();
    }

    throw Exception("Gagal mengambil data");
  }
}
