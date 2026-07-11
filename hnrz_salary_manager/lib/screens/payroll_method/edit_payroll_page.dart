import 'package:flutter/material.dart';

import '../../models/payroll_method.dart';
import '../../services/payroll_method_service.dart';

class EditPayrollPage extends StatefulWidget {
  final PayrollMethod payrollMethod;

  const EditPayrollPage({super.key, required this.payrollMethod});

  @override
  State<EditPayrollPage> createState() => _EditPayrollPageState();
}

class _EditPayrollPageState extends State<EditPayrollPage> {
  final _formKey = GlobalKey<FormState>();

  final _typeController = TextEditingController();
  final _nameController = TextEditingController();
  final _descriptionController = TextEditingController();
  late bool isActive;

  final PayrollMethodService _service = PayrollMethodService();
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    _typeController.text = widget.payrollMethod.type;
    _nameController.text = widget.payrollMethod.name;
    _descriptionController.text = widget.payrollMethod.description ?? "";
    isActive = widget.payrollMethod.isActive;
  }

  @override
  void dispose() {
    _typeController.dispose();
    _nameController.dispose();
    _descriptionController.dispose();
    super.dispose();
  }

  Future<void> _updatePayroll() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    try {
      final success = await _service.update(
        id: widget.payrollMethod.id,
        type: _typeController.text,
        name: _nameController.text,
        description: _descriptionController.text.isEmpty
            ? null
            : _descriptionController.text,
        isActive: isActive,
      );

      if (!mounted) return;
      setState(() => _isLoading = false);

      if (success) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text("Metode penggajian berhasil diperbarui"),
          ),
        );
        Navigator.pop(context, true);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text("Gagal memperbarui metode penggajian"),
          ),
        );
      }
    } catch (e) {
      if (!mounted) return;
      setState(() => _isLoading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Error: $e")),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Edit Metode Penggajian")),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              TextFormField(
                controller: _typeController,
                decoration: const InputDecoration(
                  labelText: "Tipe",
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return "Tipe wajib diisi";
                  }
                  return null;
                },
              ),

              const SizedBox(height: 20),

              TextFormField(
                controller: _nameController,
                decoration: const InputDecoration(
                  labelText: "Nama Metode",
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return "Nama wajib diisi";
                  }
                  return null;
                },
              ),

              const SizedBox(height: 20),

              TextFormField(
                controller: _descriptionController,
                maxLines: 4,
                decoration: const InputDecoration(
                  labelText: "Deskripsi",
                  border: OutlineInputBorder(),
                ),
              ),

              const SizedBox(height: 10),

              SwitchListTile(
                contentPadding: EdgeInsets.zero,
                title: const Text("Aktif"),
                value: isActive,
                onChanged: (value) {
                  setState(() => isActive = value);
                },
              ),

              const SizedBox(height: 30),

              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _isLoading ? null : _updatePayroll,
                  child: _isLoading
                      ? const SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(strokeWidth: 2),
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