import 'package:flutter/material.dart';
import '../../services/bonus_service.dart';
import 'package:month_picker_dialog/month_picker_dialog.dart';

class CreateBonusPage extends StatefulWidget {
  const CreateBonusPage({super.key});

  @override
  State<CreateBonusPage> createState() => _CreateBonusPageState();
}

class _CreateBonusPageState extends State<CreateBonusPage> {
  final _formKey = GlobalKey<FormState>();

  final namaBonusController = TextEditingController();
  final nominalBonusController = TextEditingController();
  String? selectedJenisBonus;
  DateTime? selectedPeriodeBonus;
  final TextEditingController periodeBonusController = TextEditingController();
  final keteranganController = TextEditingController();
  final BonusService _service = BonusService();

  @override
  void dispose() {
    namaBonusController.dispose();
    nominalBonusController.dispose();
    periodeBonusController.dispose();
    keteranganController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Tambah Bonus")),

      body: Padding(
        padding: const EdgeInsets.all(20),

        child: Form(
          key: _formKey,

          child: Column(
            children: [
              TextFormField(
                controller: namaBonusController,
                decoration: const InputDecoration(labelText: "Nama Bonus"),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return "Nama bonus wajib diisi";
                  }
                  return null;
                },
              ),

              const SizedBox(height: 20),

              TextFormField(
                controller: nominalBonusController,
                keyboardType: TextInputType.number,
                decoration: const InputDecoration(labelText: "Nominal Bonus"),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return "Nominal bonus wajib diisi";
                  }

                  if (double.tryParse(value) == null) {
                    return "Nominal bonus harus berupa angka";
                  }

                  return null;
                },
              ),

              const SizedBox(height: 20),

              DropdownButtonFormField<String>(
                value: selectedJenisBonus,
                decoration: const InputDecoration(
                  labelText: "Jenis Bonus",
                  border: OutlineInputBorder(),
                ),
                items: const [
                  DropdownMenuItem(value: "Tetap", child: Text("Tetap")),
                  DropdownMenuItem(value: "Variabel", child: Text("Variabel")),
                ],
                onChanged: (value) {
                  setState(() {
                    selectedJenisBonus = value;
                  });
                },
                validator: (value) {
                  if (value == null) {
                    return "Pilih jenis bonus";
                  }
                  return null;
                },
              ),

              const SizedBox(height: 20),

              TextFormField(
                controller: periodeBonusController,
                readOnly: true,
                decoration: const InputDecoration(
                  labelText: "Periode Bonus",
                  border: OutlineInputBorder(),
                  suffixIcon: Icon(Icons.calendar_month),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return "Pilih periode bonus";
                  }
                  return null;
                },
                onTap: () async {
                  final picked = await showMonthPicker(
                    context: context,
                    initialDate: DateTime.now(),
                    firstDate: DateTime(2020),
                    lastDate: DateTime(2100),
                  );

                  if (picked != null) {
                    setState(() {
                      selectedPeriodeBonus = picked;

                      // Format yang dikirim ke Laravel
                      periodeBonusController.text =
                          "${picked.year}-${picked.month.toString().padLeft(2, '0')}-01";
                    });
                  }
                },
              ),

              const SizedBox(height: 20),

              TextFormField(
                controller: keteranganController,
                maxLines: 4,
                decoration: const InputDecoration(labelText: "Keterangan"),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return "Keterangan wajib diisi";
                  }

                  return null;
                },
              ),

              const SizedBox(height: 30),

              ElevatedButton(
                onPressed: () async {
                  if (!_formKey.currentState!.validate()) {
                    return;
                  }

                  final success = await _service.create(
                    namaBonus: namaBonusController.text,
                    nominalBonus: double.parse(nominalBonusController.text),
                    jenisBonus: selectedJenisBonus!,
                    periodeBonus: periodeBonusController.text,
                    keterangan: keteranganController.text,
                  );

                  if (!mounted) return;

                  if (success) {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(
                        content: Text("Bonus berhasil ditambahkan"),
                      ),
                    );

                    Navigator.pop(context, true);
                  } else {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text("Gagal menambahkan bonus")),
                    );
                  }
                },
                child: const Text("Simpan"),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
