# 🎨 UI Components - Before & After

## Location Manager Component

### Header
```
┌─────────────────────────────────────────────────────────┐
│                                                           │
│  🗺️ Kelola Lokasi Google Maps        [+ Tambah Lokasi] │
│     Tambahkan multiple lokasi dengan radius             │
│     validasi masing-masing                              │
│                                                           │
└─────────────────────────────────────────────────────────┘
```

---

## Location Card Examples

### Valid Location (Edit Mode)
```
┌──────────────────────────────────────────────────────────┐
│ [1] Lokasi 1                      [✓ Valid]             │
│ 🔗 https://maps.app.goo.gl/...abc...                    │
│ 🔴 Radius: 500m                                         │
├──────────────────────────────────────────────────────────┤
│ 🗺️ Google Maps Link *                                   │
│ [https://www.google.com/maps?q=-6.123,106.456        ] │
│ 💡 Paste link Google Maps atau klik tombol untuk...    │
│ [🗺️ Pilih dari Peta]                                   │
│                                                          │
│ 📍 Radius Validasi (opsional)                           │
│ [300] meter                                             │
│ 💡 Radius dalam meter untuk validasi lokasi saat...    │
├──────────────────────────────────────────────────────────┤
│ [✓ Simpan Perubahan]  [✕ Batal]                       │
└──────────────────────────────────────────────────────────┘
```

### Empty Location (Edit Mode)
```
┌──────────────────────────────────────────────────────────┐
│ [2] Lokasi 2                      [⚠ Belum ada link]   │
├──────────────────────────────────────────────────────────┤
│ 🗺️ Google Maps Link *                                   │
│ [ ] (empty input, focused)                              │
│ 💡 Paste link Google Maps atau klik tombol untuk...    │
│ [🗺️ Pilih dari Peta]                                   │
│                                                          │
│ 📍 Radius Validasi (opsional)                           │
│ [ ] meter                                               │
│ 💡 Radius dalam meter untuk validasi lokasi saat...    │
├──────────────────────────────────────────────────────────┤
│ [✓ Simpan Perubahan]  [✕ Batal]                       │
└──────────────────────────────────────────────────────────┘
```

### View Mode
```
┌──────────────────────────────────────────────────────────┐
│ [1] Lokasi 1                      [✓ Valid]             │
│ 🔗 https://maps.app.goo.gl/...abc...                    │
│ [🔴 Radius: 500m]                                       │
├──────────────────────────────────────────────────────────┤
│ [✏️ Edit]  [🗑️ Hapus]                                   │
└──────────────────────────────────────────────────────────┘
```

---

## Empty State
```
┌──────────────────────────────────────────────────────────┐
│                                                           │
│                         🗺️                              │
│                                                           │
│                   Belum Ada Lokasi                       │
│                                                           │
│         Mulai dengan menambahkan lokasi Google           │
│              Maps pertama merchant Anda                  │
│                                                           │
│        [+ Tambah Lokasi Pertama]                         │
│                                                           │
└──────────────────────────────────────────────────────────┘
```

---

## Interactive Elements

### Buttons
```
Styles:
┌──────────────────────────────────────────┐
│ [+ Tambah Lokasi]      - Orange gradient │
│ [✓ Simpan Perubahan]   - Green           │
│ [✕ Batal]              - Gray            │
│ [✏️ Edit]              - Blue            │
│ [🗑️ Hapus]             - Red             │
└──────────────────────────────────────────┘

Hover Effects:
- Scale transform (small grow)
- Enhanced shadow
- Color brightening
```

### Form Inputs
```
Default State:
[https://www.google.com/maps?q=... ]
border: 2px solid #E5E7EB

Focused State:
[https://www.google.com/maps?q=... ]
border: 2px solid #FB923C
ring: 2px solid #FB923C (offset)

Error State:
[https://invalid... ]
border: 2px solid #EF4444
message: ❌ Format URL tidak valid
```

### Status Badges
```
Valid:
[✓ Valid]  ← Green background, green text

Invalid:
[⚠ Belum ada link]  ← Yellow background, yellow text
```

### Toast Notifications
```
Position: Bottom-left
Animation: Slide-up (300ms)
Duration: 3 seconds auto-dismiss

Examples:
✅ Lokasi tersimpan
✅ Lokasi dihapus
➕ Lokasi baru ditambahkan, silakan isi datanya
❌ Link Google Maps tidak boleh kosong
```

---

## Color Palette

```
Primary Colors:
- Orange-400: #FB923C (accent)
- Orange-500: #F97316 (primary)
- Orange-600: #EA580C (hover)
- Red-50:     #FEF2F2 (background)

Semantic Colors:
- Green-100:  #DCFCE7 (success bg)
- Green-800:  #166534 (success text)
- Red-100:    #FEE2E2 (error bg)
- Red-700:    #B91C1C (error text)
- Yellow-100: #FEF3C7 (warning bg)
- Yellow-800: #713F12 (warning text)
- Blue-100:   #DBEAFE (info bg)
- Blue-700:   #1D4ED8 (info text)

Neutral:
- Gray-50:   #F9FAFB (light bg)
- Gray-100:  #F3F4F6 (card bg)
- Gray-400:  #9CA3AF (hint text)
- Gray-600:  #4B5563 (secondary text)
- Gray-700:  #374151 (primary text)
- Gray-800:  #1F2937 (heading text)
```

---

## Typography

```
Labels:
- font-weight: 700 (bold)
- font-size: 0.75rem (12px)
- text-transform: uppercase
- letter-spacing: 0.05em

Field Labels:
- font-weight: 600 (semibold)
- font-size: 0.875rem (14px)
- color: #374151 (gray-700)

Hints:
- font-size: 0.75rem (12px)
- color: #6B7280 (gray-500)
- margin-top: 0.375rem

Button Text:
- font-weight: 600 (semibold)
- font-size: 0.75rem (12px)
```

---

## Spacing & Layout

```
Component Padding: 20px (5px = 0.25rem)
Card Padding: 20px
Gap between elements: 16px
Section border-width: 2px

Card Border Radius: 12px (rounded-xl)
Button Border Radius: 8px (rounded-lg)
Input Border Radius: 8px (rounded-lg)

Box Shadows:
- sm: 0 1px 2px 0 rgba(0,0,0,0.05)
- md: 0 4px 6px -1px rgba(0,0,0,0.1)
- lg: 0 10px 15px -3px rgba(0,0,0,0.1)
```

---

## Animations

```
Button Hover:
- Scale: 1.05 (5% bigger)
- Duration: 200ms
- Easing: ease-in-out

Card Active State:
- Border color fade
- Background color transition
- Duration: 200ms

Toast Notification:
- Slide-up animation
- Duration: 300ms
- Easing: ease-out

Icon Animations:
- Smooth color transition
- Rotate on hover (optional)
```

---

## Accessibility Features

- ✅ Semantic HTML
- ✅ Clear labels for inputs
- ✅ High contrast colors (WCAG AA)
- ✅ Proper button focus states
- ✅ Keyboard navigation
- ✅ ARIA labels where needed
- ✅ Clear error messages
- ✅ Touch-friendly targets (44px+ minimum)

---

## Mobile Responsive

```
Mobile (< 640px):
- Single column layout
- Full-width buttons
- Larger touch targets
- Simplified spacing

Tablet (640px - 1024px):
- Optimized spacing
- Better readable

Desktop (> 1024px):
- Full featured layout
- Enhanced spacing
```

---

**Design System**: Tailwind CSS
**Color Framework**: Consistent palette
**Status**: ✅ **PRODUCTION READY**
