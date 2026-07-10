class Bonus {
  final int id;
  final String namaBonus;
  final double nominalBonus;
  final String jenisBonus;
  final String periodeBonus;
  final String? keterangan;

  Bonus({
    required this.id,
    required this.namaBonus,
    required this.nominalBonus,
    required this.jenisBonus,
    required this.periodeBonus,
    this.keterangan,
  });

  factory Bonus.fromJson(Map<String, dynamic> json) {
    return Bonus(
      id: json['id'],
      namaBonus: json['nama_bonus'],
      nominalBonus: double.parse(json['nominal_bonus'].toString()),
      jenisBonus: json['jenis_bonus'],
      periodeBonus: json['periode_bonus'],
      keterangan: json['keterangan'],
    );
  }
}