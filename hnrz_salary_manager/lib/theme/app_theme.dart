import 'package:flutter/material.dart';

/// =====================================================================
/// HNRZ Salary Manager — Design Tokens (v2, gaya "colorful dashboard")
/// Terinspirasi dari referensi mobile admin dashboard: card statistik
/// warna solid, list item dengan ikon avatar, bottom navigation bar.
/// =====================================================================

class AppColors {
  AppColors._();

  // Aksen utama — biru vivid untuk AppBar/tombol/nav aktif
  static const Color primary = Color(0xFF4B7BEC);
  static const Color primaryDark = Color(0xFF3867D6);
  static const Color primaryLight = Color(0xFF7FA6F5);

  // Latar & permukaan
  static const Color background = Color(0xFFF5F6FA);
  static const Color surface = Color(0xFFFFFFFF);
  static const Color surfaceAlt = Color(0xFFF0F1F7);

  // Teks
  static const Color textPrimary = Color(0xFF1E2233);
  static const Color textSecondary = Color(0xFF8A8D9F);
  static const Color textDisabled = Color(0xFFB8BACA);

  // Border & divider
  static const Color border = Color(0xFFEDEEF4);
  static const Color divider = Color(0xFFEDEEF4);

  // Status (badge)
  static const Color success = Color(0xFF20BF6B);
  static const Color successBg = Color(0xFFE3F9EC);
  static const Color warning = Color(0xFFF7B731);
  static const Color warningBg = Color(0xFFFEF3DC);
  static const Color danger = Color(0xFFEB3B5A);
  static const Color dangerBg = Color(0xFFFCE4E8);
  static const Color infoBg = Color(0xFFE9EFFD);

  // Card statistik warna solid (ala referensi)
  static const Color statGreen = Color(0xFF20BF6B);
  static const Color statRed = Color(0xFFEB3B5A);
  static const Color statYellow = Color(0xFFF7B731);
  static const Color statBlue = Color(0xFF4B7BEC);
  static const Color statPurple = Color(0xFFA55EEA);
  static const Color statTeal = Color(0xFF0FB9B1);
}

class AppSpacing {
  AppSpacing._();
  static const double xs = 4;
  static const double sm = 8;
  static const double md = 16;
  static const double lg = 24;
  static const double xl = 32;
}

class AppRadius {
  AppRadius._();
  static const double sm = 10;
  static const double md = 16;
  static const double lg = 22;
}

class AppTheme {
  AppTheme._();

  static ThemeData get theme {
    final colorScheme = ColorScheme.fromSeed(
      seedColor: AppColors.primary,
      primary: AppColors.primary,
      surface: AppColors.surface,
      brightness: Brightness.light,
    );

    return ThemeData(
      useMaterial3: true,
      colorScheme: colorScheme,
      scaffoldBackgroundColor: AppColors.background,

      appBarTheme: const AppBarTheme(
        backgroundColor: AppColors.background,
        foregroundColor: AppColors.textPrimary,
        elevation: 0,
        scrolledUnderElevation: 0,
        surfaceTintColor: Colors.transparent,
        centerTitle: false,
        titleTextStyle: TextStyle(
          color: AppColors.textPrimary,
          fontSize: 20,
          fontWeight: FontWeight.w700,
        ),
        iconTheme: IconThemeData(color: AppColors.textPrimary),
      ),

      textTheme: const TextTheme(
        headlineSmall: TextStyle(
          fontSize: 22,
          fontWeight: FontWeight.w800,
          color: AppColors.textPrimary,
        ),
        titleLarge: TextStyle(
          fontSize: 18,
          fontWeight: FontWeight.w700,
          color: AppColors.textPrimary,
        ),
        titleMedium: TextStyle(
          fontSize: 15,
          fontWeight: FontWeight.w700,
          color: AppColors.textPrimary,
        ),
        bodyLarge: TextStyle(fontSize: 15, color: AppColors.textPrimary),
        bodyMedium: TextStyle(fontSize: 13, color: AppColors.textSecondary),
        labelLarge: TextStyle(fontSize: 14, fontWeight: FontWeight.w700),
      ),

      cardTheme: CardThemeData(
        color: AppColors.surface,
        elevation: 0,
        margin: EdgeInsets.zero,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(AppRadius.md),
        ),
      ),

      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: AppColors.surfaceAlt,
        contentPadding: const EdgeInsets.symmetric(
          horizontal: AppSpacing.md,
          vertical: 14,
        ),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppRadius.sm),
          borderSide: BorderSide.none,
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppRadius.sm),
          borderSide: BorderSide.none,
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppRadius.sm),
          borderSide: const BorderSide(color: AppColors.primary, width: 1.5),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppRadius.sm),
          borderSide: const BorderSide(color: AppColors.danger),
        ),
        labelStyle: const TextStyle(color: AppColors.textSecondary),
        hintStyle: const TextStyle(color: AppColors.textDisabled),
      ),

      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.primary,
          foregroundColor: Colors.white,
          disabledBackgroundColor: AppColors.textDisabled,
          elevation: 0,
          padding: const EdgeInsets.symmetric(
            horizontal: AppSpacing.lg,
            vertical: 14,
          ),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(AppRadius.sm),
          ),
          textStyle: const TextStyle(
            fontSize: 14,
            fontWeight: FontWeight.w700,
          ),
        ),
      ),
      outlinedButtonTheme: OutlinedButtonThemeData(
        style: OutlinedButton.styleFrom(
          foregroundColor: AppColors.primary,
          side: const BorderSide(color: AppColors.border),
          padding: const EdgeInsets.symmetric(
            horizontal: AppSpacing.lg,
            vertical: 14,
          ),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(AppRadius.sm),
          ),
        ),
      ),
      textButtonTheme: TextButtonThemeData(
        style: TextButton.styleFrom(
          foregroundColor: AppColors.primary,
          textStyle: const TextStyle(fontWeight: FontWeight.w700),
        ),
      ),

      dividerTheme: const DividerThemeData(
        color: AppColors.divider,
        thickness: 1,
        space: 1,
      ),

      chipTheme: ChipThemeData(
        backgroundColor: AppColors.surfaceAlt,
        labelStyle: const TextStyle(fontSize: 12, color: AppColors.textPrimary),
        side: BorderSide.none,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20),
        ),
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      ),

      dataTableTheme: DataTableThemeData(
        headingRowColor: WidgetStateProperty.all(AppColors.surfaceAlt),
        headingTextStyle: const TextStyle(
          fontWeight: FontWeight.w700,
          color: AppColors.textPrimary,
          fontSize: 13,
        ),
        dataTextStyle: const TextStyle(
          color: AppColors.textPrimary,
          fontSize: 13,
        ),
        dividerThickness: 1,
      ),

      snackBarTheme: SnackBarThemeData(
        backgroundColor: AppColors.textPrimary,
        contentTextStyle: const TextStyle(color: Colors.white),
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(AppRadius.sm),
        ),
      ),

      bottomNavigationBarTheme: const BottomNavigationBarThemeData(
        backgroundColor: AppColors.surface,
        selectedItemColor: AppColors.primary,
        unselectedItemColor: AppColors.textDisabled,
        type: BottomNavigationBarType.fixed,
        showUnselectedLabels: true,
        elevation: 0,
        selectedLabelStyle: TextStyle(fontSize: 11, fontWeight: FontWeight.w600),
        unselectedLabelStyle: TextStyle(fontSize: 11),
      ),
    );
  }
}

/// Badge status kecil (mis. status karyawan aktif/nonaktif,
/// status pembayaran gaji lunas/pending, dsb.)
class StatusBadge extends StatelessWidget {
  final String label;
  final StatusType type;

  const StatusBadge({super.key, required this.label, required this.type});

  @override
  Widget build(BuildContext context) {
    final (Color fg, Color bg) = switch (type) {
      StatusType.success => (AppColors.success, AppColors.successBg),
      StatusType.warning => (AppColors.warning, AppColors.warningBg),
      StatusType.danger => (AppColors.danger, AppColors.dangerBg),
      StatusType.info => (AppColors.primary, AppColors.infoBg),
    };

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: fg,
          fontSize: 12,
          fontWeight: FontWeight.w700,
        ),
      ),
    );
  }
}

enum StatusType { success, warning, danger, info }

/// Card statistik warna solid ala referensi (mis. "Active Projects: 23").
class StatCard extends StatelessWidget {
  final String title;
  final String value;
  final String? footer;
  final IconData? footerIcon;
  final Color color;

  const StatCard({
    super.key,
    required this.title,
    required this.value,
    required this.color,
    this.footer,
    this.footerIcon,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(AppSpacing.md),
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(AppRadius.md),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 13,
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 10),
          Text(
            value,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 26,
              fontWeight: FontWeight.w800,
            ),
          ),
          if (footer != null) ...[
            const SizedBox(height: 6),
            Row(
              children: [
                if (footerIcon != null)
                  Icon(footerIcon, size: 12, color: Colors.white.withValues(alpha: 0.9)),
                if (footerIcon != null) const SizedBox(width: 3),
                Flexible(
                  child: Text(
                    footer!,
                    style: TextStyle(
                      color: Colors.white.withValues(alpha: 0.9),
                      fontSize: 11,
                      fontWeight: FontWeight.w500,
                    ),
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
              ],
            ),
          ],
        ],
      ),
    );
  }
}

/// Avatar ikon kotak bulat (ala icon list "Website Launch", dsb.)
class IconAvatar extends StatelessWidget {
  final IconData icon;
  final Color color;
  final double size;

  const IconAvatar({
    super.key,
    required this.icon,
    required this.color,
    this.size = 44,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: size,
      height: size,
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(AppRadius.sm),
      ),
      child: Icon(icon, color: Colors.white, size: size * 0.5),
    );
  }
}