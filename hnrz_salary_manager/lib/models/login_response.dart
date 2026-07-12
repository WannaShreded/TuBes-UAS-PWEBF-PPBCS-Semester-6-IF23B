class LoginResponse {

  final bool success;
  final String message;
  final String token;
  final List<String> roles;

  LoginResponse({
    required this.success,
    required this.message,
    required this.token,
    required this.roles,
  });

  factory LoginResponse.fromJson(Map<String, dynamic> json) {

    return LoginResponse(
      success: json["success"],
      message: json["message"],
      token: json["token"] ?? "",
      roles: (json["roles"] as List? ?? [])
          .map((role) => role.toString())
          .toList(),
    );

  }

}
