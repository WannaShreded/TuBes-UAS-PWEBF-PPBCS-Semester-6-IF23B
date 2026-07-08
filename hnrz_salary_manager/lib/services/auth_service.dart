import 'dart:convert';

import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

import '../models/login_response.dart';
import 'api_client.dart';

class AuthService {
  Future<LoginResponse?> login({
    required String email,
    required String password,
  }) async {
    final response = await http.post(
      Uri.parse("${ApiClient.baseUrl}/login"),
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
      },
      body: jsonEncode({
        "email": email,
        "password": password,
      }),
    );

    if (response.statusCode == 200) {
      final loginResponse =
          LoginResponse.fromJson(jsonDecode(response.body));

      await saveToken(loginResponse.token);

      return loginResponse;
    }

    return null;
  }

  Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString("token", token);
  }

  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString("token");
  }

  Future<bool> isLoggedIn() async {
    final token = await getToken();
    return token != null && token.isNotEmpty;
  }

  Future<void> logout() async {
    final token = await getToken();

    if (token != null) {
      await http.post(
        Uri.parse("${ApiClient.baseUrl}/logout"),
        headers: {
          "Authorization": "Bearer $token",
          "Accept": "application/json",
        },
      );
    }

    final prefs = await SharedPreferences.getInstance();
    await prefs.remove("token");
  }
}