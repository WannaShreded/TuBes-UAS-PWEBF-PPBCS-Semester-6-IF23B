import 'package:flutter/material.dart';
import 'package:month_picker_dialog/month_picker_dialog.dart';

import '../../models/bonus.dart';
import '../../services/bonus_service.dart';

class EditBonusPage extends StatefulWidget {
  final Bonus bonus;

  const EditBonusPage({
    super.key,
    required this.bonus,
  });

  @override
  State<EditBonusPage> createState() => _EditBonusPageState();
}

class _EditBonusPageState extends State<EditBonusPage> {
  final _formKey = GlobalKey<FormState>();

  final _namaBonusController = TextEditingController();
  final _nominalBonusController = TextEditingController();
  String? selectedJenisBonus;
  DateTime? selectedPeriodeBonus;
  final TextEditingController _periodeBonusController = TextEditingController();
  final _keteranganController = TextEditingController();

  final BonusService _service = BonusService();

  bool _isLoading = false;

  @override
  void initState() {
    super.initState();

    _namaBonusController.text = widget.bonus.namaBonus;
    _nominalBonusController.text = widget.bonus.nominalBonus.toString();
    selectedJenisBonus = widget.bonus.jenisBonus;
    _periodeBonusController.text = widget.bonus.periodeBonus;
    _keteranganController.text = widget.bonus.keterangan ?? "";
  }

  @override
  void dispose() {
    _namaBonusController.dispose();
    _nominalBonusController.dispose();
    _periodeBonusController.dispose();
    _keteranganController.dispose();
    super.dispose();
  }

  Future<void> _updateBonus() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _isLoading = true;
    });

    final success = await _service.update(
      id: widget.bonus.id,
      namaBonus: _namaBonusController.text,
      nominalBonus: double.parse(_nominalBonusController.text),
      jenisBonus: selectedJenisBonus!,
      periodeBonus: _periodeBonusController.text,
      keterangan: _keteranganController.text,
    );

    if (!mounted) return;

    setState(() {
      _isLoading = false;
    });

    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text("Bonus berhasil diperbarui"),
        ),
      );

      Navigator.pop(context, true);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text("Gagal memperbarui bonus"),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Edit Bonus"),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              TextFormField(
                controller: _namaBonusController,
                decoration: const InputDecoration(
                  labelText: "Nama Bonus",
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return "Nama bonus wajib diisi";
                  }
                  return null;
                },
              ),

              const SizedBox(height: 20),

              TextFormField(
                controller: _nominalBonusController,
                keyboardType: TextInputType.number,
                decoration: const InputDecoration(
                  labelText: "Nominal Bonus",
                  border: OutlineInputBorder(),
                ),
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
                controller: _periodeBonusController,
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
                      _periodeBonusController.text =
                          "${picked.year}-${picked.month.toString().padLeft(2, '0')}-01";
                    });
                  }
                },
              ),

              const SizedBox(height: 20),

              TextFormField(
                controller: _keteranganController,
                maxLines: 4,
                decoration: const InputDecoration(
                  labelText: "Keterangan",
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return "Keterangan wajib diisi";
                  }

                  return null;
                },
              ),

              const SizedBox(height: 30),

              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _isLoading ? null : _updateBonus,
                  child: _isLoading
                      ? const SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                          ),
                        )
                      : const Text("Update"),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}