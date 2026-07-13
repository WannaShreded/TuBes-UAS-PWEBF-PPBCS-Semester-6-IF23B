import 'package:flutter/material.dart';
import 'package:month_picker_dialog/month_picker_dialog.dart';

import '../../services/bonus_service.dart';
import '../../theme/app_theme.dart';
import '../../widgets/common_widgets.dart';

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

  bool _isLoading = false;

  @override
  void dispose() {
    namaBonusController.dispose();
    nominalBonusController.dispose();
    periodeBonusController.dispose();
    keteranganController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    final success = await _service.create(
      namaBonus: namaBonusController.text,
      nominalBonus: double.parse(nominalBonusController.text),
      jenisBonus: selectedJenisBonus!,
      periodeBonus: periodeBonusController.text,
      keterangan: keteranganController.text,
    );

    if (!mounted) return;

    setState(() => _isLoading = false);

    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Bonus berhasil ditambahkan")),
      );
      Navigator.pop(context, true);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Gagal menambahkan bonus")),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Tambah Bonus")),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(AppSpacing.md),
        child: Form(
          key: _formKey,
          child: FormCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const FormSectionLabel("Informasi Bonus"),
                TextFormField(
                  controller: namaBonusController,
                  decoration: const InputDecoration(
                    labelText: "Nama Bonus",
                    prefixIcon: Icon(Icons.card_giftcard_outlined),
                  ),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return "Nama bonus wajib diisi";
                    }
                    return null;
                  },
                ),
                const SizedBox(height: AppSpacing.md),
                TextFormField(
                  controller: nominalBonusController,
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(
                    labelText: "Nominal Bonus",
                    prefixIcon: Icon(Icons.payments_outlined),
                    prefixText: "Rp ",
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
                const SizedBox(height: AppSpacing.md),
                DropdownButtonFormField<String>(
                  initialValue: selectedJenisBonus,
                  decoration: const InputDecoration(
                    labelText: "Jenis Bonus",
                    prefixIcon: Icon(Icons.category_outlined),
                  ),
                  items: const [
                    DropdownMenuItem(value: "Tetap", child: Text("Tetap")),
                    DropdownMenuItem(
                      value: "Variabel",
                      child: Text("Variabel"),
                    ),
                  ],
                  onChanged: (value) {
                    setState(() {
                      selectedJenisBonus = value;
                    });
                  },
                  validator: (value) {
                    if (value == null) return "Pilih jenis bonus";
                    return null;
                  },
                ),
                const SizedBox(height: AppSpacing.md),
                TextFormField(
                  controller: periodeBonusController,
                  readOnly: true,
                  decoration: const InputDecoration(
                    labelText: "Periode Bonus",
                    prefixIcon: Icon(Icons.calendar_month_outlined),
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
                        periodeBonusController.text =
                            "${picked.year}-${picked.month.toString().padLeft(2, '0')}-01";
                      });
                    }
                  },
                ),
                const SizedBox(height: AppSpacing.md),
                TextFormField(
                  controller: keteranganController,
                  maxLines: 4,
                  decoration: const InputDecoration(
                    labelText: "Keterangan",
                    alignLabelWithHint: true,
                  ),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return "Keterangan wajib diisi";
                    }
                    return null;
                  },
                ),
                const SizedBox(height: AppSpacing.lg),
                SizedBox(
                  height: 48,
                  child: ElevatedButton(
                    onPressed: _isLoading ? null : _submit,
                    child: _isLoading
                        ? const SizedBox(
                            width: 20,
                            height: 20,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              color: Colors.white,
                            ),
                          )
                        : const Text("Simpan"),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}