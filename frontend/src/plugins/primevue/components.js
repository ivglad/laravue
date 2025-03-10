export default {
  // --------------------------------------------------------------------------
  // Form
  // --------------------------------------------------------------------------
  // AutoComplete
  autocomplete: {},
  // CascadeSelect
  cascadeselect: {},
  // Checkbox
  checkbox: {
    width: '2rem',
    height: '2rem',
    borderRadius: '{border.radius.xs}',
    sm: {
      width: '1.4rem',
      height: '1.4rem',
    },
    lg: {
      width: '2.4rem',
      height: '2.4rem',
    },
    icon: {
      size: '1.2rem',
      smSize: '0.8rem',
      lgSize: '1.6rem',
    },
    colorScheme: {
      light: {
        borderColor: '{primary.200}',
        hoverBorderColor: '{primary.color}',
        checked: {
          background: '{surface.0}',
          hoverBackground: '{surface.0}',
          borderColor: '{primary.color}',
          hoverBorderColor: '{primary.color}',
        },
        icon: {
          color: '{primary.color}',
          checkedColor: '{primary.color}',
          checkedHoverColor: '{primary.color}',
          disabledColor: '{surface.200}',
        },
      },
    },
  },
  // ColorPicker
  colorpicker: {
    previewWidth: '3rem',
    previewHeight: '3rem',
  },
  // DatePicker
  datepicker: {
    select: {
      monthPadding: '0.5rem 1rem',
      yearPadding: '0.5rem 1rem',
    },
    titleFontWeight: '700',
    monthViewMargin: '1rem 0',
    dayViewMargin: '1rem 0',
    weekDayFontWeight: '700',
    date: {
      width: '3rem',
      height: '3rem',
      borderRadius: '{border.radius.sm}',
    },
    colorScheme: {
      light: {
        headerColor: '{primary.color}',
        select: {
          month: {
            color: '{primary.color}',
            hoverColor: '{primary.700}',
            hoverBackground: 'transparent',
          },
          year: {
            color: '{primary.color}',
            hoverColor: '{primary.700}',
            hoverBackground: 'transparent',
          },
        },
        date: {
          hoverColor: '{primary.contrastColor}',
          hoverBackground: '{primary.color}',
          rangeSelectedColor: '{primary.contrastColor}',
          rangeSelectedBackground: '{primary.color}',
        },
        today: {
          color: '{primary.color}',
          background: 'transparent',
        },
      },
    },
    app: {
      day: {
        fontSize: '{app.font.size}',
        disabledColor: '{app.disabled.color}',
      },
    },
  },
  // Editor
  editor: {},
  // FloatLabel
  floatlabel: {
    activeFontSize: '1.2rem',
    overActiveTop: '-1.7rem',
  },
  // IconField
  iconfield: {},
  // IftaLabel
  iftalabel: {},
  // InputGroup
  inputgroup: {},
  // InputMask
  inputmask: {},
  // InputNumber
  inputnumber: {},
  // InputOtp
  inputotp: {},
  // InputText
  inputtext: {
    app: {
      minWidth: '10rem',
      minHeight: '4.2rem',
      sm: {
        minWidth: '6rem',
        minHeight: '3.2rem',
        fontSize: '1.2rem',
      },
      lg: {
        minHeight: '5.2rem',
      }
    },
  },
  // KeyFilter
  keyfilter: {},
  // Knob
  knob: {},
  // Listbox
  listbox: {},
  // MultiSelect
  multiselect: {
    chipBorderRadius: '10rem',
    sm: {
      fontSize: '{app.font.size}',
    },
  },
  // Password
  password: {},
  // RadioButton
  radiobutton: {
    width: '2rem',
    height: '2rem',
    sm: {
      width: '1.4rem',
      height: '1.4rem',
    },
    lg: {
      width: '2.4rem',
      height: '2.4rem',
    },
    icon: {
      size: '1.2rem',
      smSize: '0.8rem',
      lgSize: '1.6rem',
    },
    colorScheme: {
      light: {
        checked: {
          background: '{surface.0}',
          hoverBackground: '{surface.0}',
        },
        icon: {
          color: '{primary.color}',
          checkedColor: '{primary.color}',
          checkedHoverColor: '{primary.color}',
          disabledColor: '{surface.200}',
        },
      },
    },
  },
  // Rating
  rating: {},
  // Select
  select: {
    sm: {
      fontSize: '{app.font.size}',
    },
    app: {
      width: '20rem',
      minWidth: 'fit-content',
      maxWidth: '40rem',
      height: '4.2rem',
      minHeight: 'fit-content',
      maxHeight: '100%',
      smHeight: '3.2rem',
      lgHeight: '5.2rem',
      dropdownIconSize: '{app.font.size}',
    },
  },
  // SelectButton
  selectbutton: {},
  // Slider
  slider: {},
  // Textarea
  textarea: {
    app: {
      width: '25rem',
      minWidth: '10rem',
      maxWidth: '40rem',
    },
  },
  // ToggleButton
  togglebutton: {
    padding: '0.8rem 0.8rem',
    contentTop: '0.3rem',
  },
  // ToggleSwitch
  toggleswitch: {
    width: '3.4rem',
    height: '2rem',
    handle: {
      size: '1.4rem',
    },
    colorScheme: {
      light: {
        background: '{surface.0}',
        hoverBackground: '{surface.0}',
        checkedBackground: '{surface.0}',
        checkedHoverBackground: '{surface.0}',
        borderColor: '{surface.200}',
        hoverBorderColor: '{primary.color}',
        checkedBorderColor: '{primary.color}',
        checkedHoverBorderColor: '{primary.color}',
        handle: {
          background: '{surface.200}',
          hoverBackground: '{surface.200}',
          checkedBackground: '{primary.color}',
          checkedHoverBackground: '{primary.color}',
          disabledBackground: '{surface.100}',
        },
      },
    },
  },
  // TreeSelect
  treeselect: {},
  // --------------------------------------------------------------------------

  // --------------------------------------------------------------------------
  // Button
  // --------------------------------------------------------------------------
  // Button
  button: {
    iconOnlyWidth: 'fit-content',
    colorScheme: {
      light: {
        primary: {
          color: '{primary.contrast.color}',
          hoverColor: '{primary.contrast.color}',
          activeColor: '{primary.contrast.color}',
          borderColor: '{primary.500}',
          hoverBorderColor: '{primary.600}',
          activeBorderColor: '{primary.700}',
          background: '{primary.500}',
          hoverBackground: '{primary.600}',
          activeBackground: '{primary.700}',
        },
        secondary: {
          color: '{primary.contrast.color}',
          hoverColor: '{primary.contrast.color}',
          activeColor: '{primary.contrast.color}',
          borderColor: '{surface.400}',
          hoverBorderColor: '{surface.500}',
          activeBorderColor: '{surface.600}',
          background: '{surface.400}',
          hoverBackground: '{surface.500}',
          activeBackground: '{surface.600}',
        },
        success: {
          color: '{primary.contrast.color}',
          hoverColor: '{primary.contrast.color}',
          activeColor: '{primary.contrast.color}',
          borderColor: '{green.400}',
          hoverBorderColor: '{green.500}',
          activeBorderColor: '{green.600}',
          background: '{green.400}',
          hoverBackground: '{green.500}',
          activeBackground: '{green.600}',
        },
        info: {
          color: '{primary.contrast.color}',
          hoverColor: '{primary.contrast.color}',
          activeColor: '{primary.contrast.color}',
          borderColor: '{blue.400}',
          hoverBorderColor: '{blue.500}',
          activeBorderColor: '{blue.600}',
          background: '{blue.400}',
          hoverBackground: '{blue.500}',
          activeBackground: '{blue.600}',
        },
        warn: {
          color: '{primary.contrast.color}',
          hoverColor: '{primary.contrast.color}',
          activeColor: '{primary.contrast.color}',
          borderColor: '{orange.400}',
          hoverBorderColor: '{orange.500}',
          activeBorderColor: '{orange.600}',
          background: '{orange.400}',
          hoverBackground: '{orange.500}',
          activeBackground: '{orange.600}',
        },
        help: {
          color: '{primary.contrast.color}',
          hoverColor: '{primary.contrast.color}',
          activeColor: '{primary.contrast.color}',
          borderColor: '{purple.400}',
          hoverBorderColor: '{purple.500}',
          activeBorderColor: '{purple.600}',
          background: '{purple.400}',
          hoverBackground: '{purple.500}',
          activeBackground: '{purple.600}',
        },
        danger: {
          color: '{primary.contrast.color}',
          hoverColor: '{primary.contrast.color}',
          activeColor: '{primary.contrast.color}',
          borderColor: '{red.400}',
          hoverBorderColor: '{red.500}',
          activeBorderColor: '{red.600}',
          background: '{red.400}',
          hoverBackground: '{red.500}',
          activeBackground: '{red.600}',
        },
        contrast: {
          color: '{primary.contrast.color}',
          hoverColor: '{primary.contrast.color}',
          activeColor: '{primary.contrast.color}',
          borderColor: '{gray.500}',
          hoverBorderColor: '{gray.700}',
          activeBorderColor: '{gray.800}',
          background: '{gray.500}',
          hoverBackground: '{gray.700}',
          activeBackground: '{gray.800}',
        },
        outlined: {
          primary: {
            color: '{primary.500}',
            hoverColor: '{primary.700}',
            activeColor: '{primary.800}',
            borderColor: '{primary.500}',
            hoverBorderColor: '{primary.700}',
            activeBorderColor: '{primary.800}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          secondary: {
            color: '{surface.400}',
            hoverColor: '{surface.600}',
            activeColor: '{surface.700}',
            borderColor: '{surface.400}',
            hoverBorderColor: '{surface.600}',
            activeBorderColor: '{surface.700}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          success: {
            color: '{green.400}',
            hoverColor: '{green.500}',
            activeColor: '{green.600}',
            borderColor: '{green.400}',
            hoverBorderColor: '{green.500}',
            activeBorderColor: '{green.600}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          info: {
            color: '{blue.400}',
            hoverColor: '{blue.500}',
            activeColor: '{blue.600}',
            borderColor: '{blue.400}',
            hoverBorderColor: '{blue.500}',
            activeBorderColor: '{blue.600}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          warn: {
            color: '{orange.400}',
            hoverColor: '{orange.500}',
            activeColor: '{orange.600}',
            borderColor: '{orange.400}',
            hoverBorderColor: '{orange.500}',
            activeBorderColor: '{orange.600}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          help: {
            color: '{purple.400}',
            hoverColor: '{purple.500}',
            activeColor: '{purple.600}',
            borderColor: '{purple.400}',
            hoverBorderColor: '{purple.500}',
            activeBorderColor: '{purple.600}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          danger: {
            color: '{red.400}',
            hoverColor: '{red.500}',
            activeColor: '{red.600}',
            borderColor: '{red.400}',
            hoverBorderColor: '{red.500}',
            activeBorderColor: '{red.600}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          contrast: {
            color: '{gray.500}',
            hoverColor: '{gray.700}',
            activeColor: '{gray.800}',
            borderColor: '{gray.500}',
            hoverBorderColor: '{gray.700}',
            activeBorderColor: '{gray.800}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
        },
        text: {
          primary: {
            color: '{primary.500}',
            hoverColor: '{primary.700}',
            activeColor: '{primary.800}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          secondary: {
            color: '{surface.500}',
            hoverColor: '{surface.700}',
            activeColor: '{surface.800}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          success: {
            color: '{green.500}',
            hoverColor: '{green.700}',
            activeColor: '{green.800}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          info: {
            color: '{blue.500}',
            hoverColor: '{blue.700}',
            activeColor: '{blue.800}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          warn: {
            color: '{orange.500}',
            hoverColor: '{orange.700}',
            activeColor: '{orange.800}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          help: {
            color: '{purple.500}',
            hoverColor: '{purple.700}',
            activeColor: '{purple.800}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          danger: {
            color: '{red.500}',
            hoverColor: '{red.700}',
            activeColor: '{red.800}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          contrast: {
            color: '{gray.500}',
            hoverColor: '{gray.700}',
            activeColor: '{gray.800}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
        },
      },
    },
    app: {
      width: '14rem',
      height: '4.2rem',
      iconPadding: '0.5rem',
      textPadding: '0.5rem',
      sm: {
        width: '8rem',
        height: '3.2rem',
        fontSize: '1.2rem',
      },
      lg: {
        width: '16rem',
        height: '5.2rem',
        fontSize: '1.6rem',
      }
    },
  },
  // SpeedDial
  speeddial: {},
  // SplitButton
  splitbutton: {},
  // --------------------------------------------------------------------------

  // --------------------------------------------------------------------------
  // Data
  // --------------------------------------------------------------------------
  // DataTable
  datatable: {
    rowToggleButtonSize: '3rem',
    sortIconSize: '1.2rem',
    colorScheme: {
      light: {
        headerCellBorderColor: 'transparent',
        headerCellBackground: 'transparent',
        bodyCellBorderColor: 'transparent',
        footerCellBackground: 'transparent',
        row: {
          toggleButtonHoverBackground: 'transparent',
        },
      },
    },
  },
  // DataView
  dataview: {},
  // OrderList
  orderlist: {},
  // OrgChart
  orgchart: {},
  // Paginator
  paginator: {
    colorScheme: {
      light: {
        background: 'transparent',
      },
    },
    app: {
      minWidth: '6rem',
      maxWidth: 'fit-content',
    },
  },
  // PickList
  picklist: {},
  // Timeline
  timeline: {},
  // Tree
  tree: {},
  // TreeTable
  treetable: {},
  // VirtualScroller
  virtualscroller: {},
  // --------------------------------------------------------------------------

  // --------------------------------------------------------------------------
  // Panel
  // --------------------------------------------------------------------------
  // Accordion
  accordion: {},
  // Card
  card: {
    title: {
      fontSize: '1.6rem',
      fontWeight: '600',
    },
    bodyGap: '1rem',
    colorScheme: {
      light: {
        subtitleColor: '{surface.300}',
      },
    },
  },
  // Deferred
  deferred: {},
  // Divider
  divider: {
    colorScheme: {
      light: {
        contentBackground: '{surface.50}',
      },
    },
  },
  // Fieldset
  fieldset: {
    colorScheme: {
      light: {
        legend: {
          background: 'transparent',
          hoverBackground: 'transparent',
        },
      },
    },
  },
  // Panel
  panel: {},
  // ScrollPanel
  scrollpanel: {
    bar: {
      size: '0.6rem',
      borderRadius: '{border.radius.md}',
    },
    colorScheme: {
      light: {
        barBackground: '{primary.300}',
      },
    },
  },
  // Splitter
  splitter: {},
  // Stepper
  stepper: {
    stepNumber: {
      size: '4rem',
      fontSize: '1.6rem',
    },
    colorScheme: {
      light: {
        steppanelBackground: 'transparent',
        stepNumber: {
          activeColor: '{primary.contrastColor}',
          activeBorderColor: '{stepper.step.title.active.color}',
          activeBackground: '{stepper.step.title.active.color}',
        },
      },
    },
  },
  // Tabs
  tabs: {
    tablist: {
      border: {
        width: '0 0 3px 0',
        color: 'transparent',
      },
    },
    tab: {
      margin: '0 0 -3px 0',
      border: {
        width: '0 0 3px 0',
        color: 'transparent',
      },
      color: '{text.color}',
      hoverColor: '{primary.color}',
      hoverBorderColor: 'transparent',
    },
    active: {
      barHeight: '3px',
      barBottom: '-3px',
    },
    colorScheme: {
      light: {
        tablistBackground: '{surface.50}',
        tabpanelBackground: '{surface.50}',
        nav: {
          button: {
            color: '{primary.color}',
            hoverColor: '{primary.hover.color}',
            background: '{surface.50}',
            shadow: '0px 0px 10px 10px {surface.50}',
          },
        },
      },
    },
  },
  // Toolbar
  toolbar: {},
  // --------------------------------------------------------------------------

  // --------------------------------------------------------------------------
  // Overlay
  // --------------------------------------------------------------------------
  // ConfirmDialog
  confirmdialog: {},
  // ConfirmPopup
  confirmpopup: {
    arrowOffset: 'calc({confirmpopupBorderRadius} * 2.5)',
  },
  // Dialog
  dialog: {
    title: {
      fontSize: '1.6rem',
      fontWeight: '600',
    },
    footerGap: '1rem',
  },
  // Drawer
  drawer: {},
  // DinamicDialog
  dinamicdialog: {},
  // Popover
  popover: {
    arrowOffset: 'calc({popoverBorderRadius} * 2)',
  },
  // Tooltip (не работает из-за бага PrimeVue)
  tooltip: {
    maxWidth: '30rem',
    gutter: '0.5rem',
    colorScheme: {
      light: {
        color: '{text.color}',
        background: '{surface.50}',
      },
    },
    app: {
      maxWidth: '30rem',
      fontSize: '{app.font.size.sm}',
      borderColor: '{surface.0}',
    },
  },
  // --------------------------------------------------------------------------

  // --------------------------------------------------------------------------
  // File
  // --------------------------------------------------------------------------
  // Upload
  fileupload: {
    headerPadding: '1rem 1rem 0.5rem 1rem',
    contentPadding: '0.5rem 1rem 1rem 1rem',
  },
  // --------------------------------------------------------------------------

  // --------------------------------------------------------------------------
  // Menu
  // --------------------------------------------------------------------------
  // Breadcrumb
  breadcrumb: {},
  // ContextMenu
  contextmenu: {},
  // Dock
  dock: {},
  // Menu
  menu: {},
  // Menubar
  menubar: {},
  // MegaMenu
  megamenu: {},
  // PanelMenu
  panelmenu: {},
  // TieredMenu
  tieredmenu: {
    itemPadding: '1rem 1rem',
    listGap: '0.2rem',
    submenuIconSize: '1.4rem',
  },
  // --------------------------------------------------------------------------

  // --------------------------------------------------------------------------
  // Chart
  // --------------------------------------------------------------------------
  // Chart.js
  // --------------------------------------------------------------------------

  // --------------------------------------------------------------------------
  // Messages
  // --------------------------------------------------------------------------
  // Message
  message: {
    text: {
      fontSize: 'inherit',
      smFontSize: '1.2rem',
      lgFontSize: '1.6rem',
      fontWeight: 'inherit',
    },
  },
  // Toast
  toast: {
    width: '30rem',
    contentPadding: '1.5rem',
    contentGap: '1.5rem',
    summary: {
      fontWeight: '700',
      fontSize: '1.6rem',
    },
    detail: {
      fontWeight: '400',
      fontSize: 'initial',
    },
    colorScheme: {
      light: {
        success: {
          color: '{text.color}',
          background: '{content.background}',
          borderColor: '{green.500}',
        },
        info: {
          color: '{text.color}',
          background: '{content.background}',
          borderColor: '{sky.500}',
        },
        warn: {
          color: '{text.color}',
          background: '{content.background}',
          borderColor: '{amber.500}',
        },
        error: {
          color: '{text.color}',
          background: '{content.background}',
          borderColor: '{red.400}',
        },
        secondary: {
          color: '{text.color}',
          background: '{content.background}',
          borderColor: '{gray.500}',
        },
        contrast: {
          color: '{primary.color}',
          detailColor: '{text.color}',
          background: '{content.background}',
          borderColor: '{primary.color}',
        },
      },
    },
  },
  // --------------------------------------------------------------------------

  // --------------------------------------------------------------------------
  // Media
  // --------------------------------------------------------------------------
  // Carousel
  carousel: {},
  // Galleria
  galleria: {},
  // Image
  image: {},
  // ImageCompare
  imagecompare: {},
  // --------------------------------------------------------------------------

  // --------------------------------------------------------------------------
  // Misc
  // --------------------------------------------------------------------------
  // Avatar
  avatar: {},
  // Badge
  badge: {
    fontSize: '{app.font.size.sm}',
    fontWeight: '400',
    minWidth: '1.8rem',
    height: '1.8rem',
    padding: '0.5rem 0.5rem',
    borderRadius: '10rem',
    xl: {
      fontSize: '{app.font.size.lg}',
      minWidth: '2.2rem',
      height: '2.2rem',
    },
    lg: {
      fontSize: '{app.font.size}',
      minWidth: '2rem',
      height: '2rem',
    },
    sm: {
      fontSize: '{app.font.size.sm}',
      minWidth: '1.6rem',
      height: '1.6rem',
    },
    dotSize: '0.6rem',
    colorScheme: {
      light: {
        primaryBackground: '{surface.500}',
      },
    },
  },
  // BlockUI
  blockui: {},
  // Chip
  chip: {
    paddingX: '0.3rem',
    paddingY: '0.3rem',
    gap: '0',
    borderRadius: '10rem',
    iconSize: '2rem',
    remove: {
      iconSize: '2rem',
    },
    colorScheme: {
      light: {
        iconColor: '{surface.500}',
        removeIconColor: '{surface.500}',
      },
    },
    app: {
      fontSize: '{app.font.size.sm}',
    },
  },
  // Inplace
  inplace: {},
  // MeterGroup
  metergroup: {},
  // ProgressBar
  progressbar: {},
  // ProgressSpinner
  progressspinner: {
    colorScheme: {
      light: {
        root: {
          'color.1': '{primary.color}',
          'color.2': '{primary.color}',
          'color.3': '{primary.color}',
          'color.4': '{primary.color}',
        },
      },
    },
  },
  // ScrollTop
  scrolltop: {},
  // Skeleton
  skeleton: {},
  // Tag
  tag: {},
  // Terminal
  terminal: {},
  // --------------------------------------------------------------------------
}
