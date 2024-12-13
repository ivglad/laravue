export const components = {
  button: {
    iconOnlyWidth: 'fit-content',
    colorScheme: {
      light: {
        primary: {
          color: '{primary.contrast.color}',
          borderColor: '{primary.500}',
          background: '{primary.500}',
          hoverColor: '{primary.contrast.color}',
          hoverBorderColor: '{primary.600}',
          hoverBackground: '{primary.600}',
          activeColor: '{primary.contrast.color}',
          activeBorderColor: '{primary.700}',
          activeBackground: '{primary.700}',
        },
        secondary: {
          color: '{primary.contrast.color}',
          borderColor: '{surface.400}',
          background: '{surface.400}',
          hoverColor: '{primary.contrast.color}',
          hoverBorderColor: '{surface.500}',
          hoverBackground: '{surface.500}',
          activeColor: '{primary.contrast.color}',
          activeBorderColor: '{surface.600}',
          activeBackground: '{surface.600}',
        },
        success: {
          color: '{primary.contrast.color}',
          borderColor: '{green.400}',
          background: '{green.400}',
          hoverColor: '{primary.contrast.color}',
          hoverBorderColor: '{green.500}',
          hoverBackground: '{green.500}',
          activeColor: '{primary.contrast.color}',
          activeBorderColor: '{green.600}',
          activeBackground: '{green.600}',
        },
        danger: {
          color: '{primary.contrast.color}',
          borderColor: '{red.400}',
          background: '{red.400}',
          hoverColor: '{primary.contrast.color}',
          hoverBorderColor: '{red.500}',
          hoverBackground: '{red.500}',
          activeColor: '{primary.contrast.color}',
          activeBorderColor: '{red.600}',
          activeBackground: '{red.600}',
        },
        outlined: {
          primary: {
            color: '{primary.500}',
            borderColor: '{primary.500}',
            hoverBorderColor: '{primary.600}',
            hoverBackground: 'transparent',
            activeBorderColor: '{primary.700}',
            activeBackground: 'transparent',
          },
          secondary: {
            color: '{surface.400}',
            borderColor: '{surface.400}',
            hoverBorderColor: '{surface.500}',
            hoverBackground: 'transparent',
            activeBorderColor: '{surface.600}',
            activeBackground: 'transparent',
          },
          success: {
            color: '{green.400}',
            borderColor: '{green.400}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          info: {
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          warn: {
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          help: {
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          danger: {
            color: '{red.400}',
            borderColor: '{red.400}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
        },
        text: {
          primary: {
            color: '{primary.500}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          secondary: {
            color: '{surface.400}',
            hoverColor: '{surface.500}',
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          success: {
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          info: {
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          warn: {
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          help: {
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
          danger: {
            hoverBackground: 'transparent',
            activeBackground: 'transparent',
          },
        },
      },
    },
    css: ({ dt }) => `
      .p-button-outlined {
        &.app-button-outlined-hover-fill {
          &:not(:disabled):hover {
            color: ${dt('button.primary.color')};
            border-color: ${dt('button.outlined.primary.hover.border.color')};
            background: ${dt('button.outlined.primary.hover.border.color')};
          }
          &:not(:disabled):active {
            color: ${dt('button.primary.color')};
            border-color: ${dt('button.outlined.primary.active.border.color')};
            background: ${dt('button.primary.active.border.color')};
          }
        }
        &.app-button-outlined-secondary-hover-fill {
          &:not(:disabled):hover {
            color: ${dt('button.secondary.color')};
            border-color: ${dt('button.outlined.secondary.hover.border.color')};
            background: ${dt('button.outlined.secondary.hover.border.color')};
          }
          &:not(:disabled):active {
            color: ${dt('button.secondary.color')};
            border-color: ${dt(
              'button.outlined.secondary.active.border.color',
            )};
            background: ${dt('button.secondary.active.border.color')};
          }
        }
      }
    `,
  },
  message: {
    text: {
      fontSize: 'inherit',
      smFontSize: '1.2rem',
      lgFontSize: '1.6rem',
      fontWeight: 'inherit',
    },
  },
  floatlabel: {
    activeFontSize: '1.2rem',
  },
  checkbox: {
    width: '16px',
    height: '16px',
    borderRadius: '{border.radius.sm}',
    sm: {
      width: '12px',
      height: '12px',
    },
    lg: {
      width: '20px',
      height: '20px',
    },
    icon: {
      smSize: '6px',
      size: '10px',
      lgSize: '12px',
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
  radiobutton: {
    width: '16px',
    height: '16px',
    sm: {
      width: '12px',
      height: '12px',
    },
    lg: {
      width: '20px',
      height: '20px',
    },
    icon: {
      smSize: '6px',
      size: '8px',
      lgSize: '10px',
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
  toggleswitch: {
    width: '34px',
    height: '20px',
    handle: {
      size: '14px',
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
  togglebutton: {
    padding: '0.75rem 1rem',
    contentTop: '0.3rem',
  },
  select: {
    sm: {
      fontSize: '1.4rem',
    },
  },
  multiselect: {
    chipBorderRadius: '10rem',
    sm: {
      fontSize: '1.4rem',
    },
  },
  chip: {
    paddingX: '0.3rem',
    paddingY: '0.3rem',
    iconSize: '2rem',
    remove: {
      iconSize: '2rem',
    },
    colorScheme: {
      light: {
        iconColor: '{surface.500}',
        remove: {
          iconColor: '{surface.500}',
        },
      },
    },
  },
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
      borderRadius: '{border.radius.lg}',
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
  },
  divider: {
    colorScheme: {
      light: {
        contentBackground: '{surface.50}',
      },
    },
  },
  chip: {
    paddingX: '0.3rem',
    paddingY: '0.3rem',
    gap: '0',
    borderRadius: '10rem',
    colorScheme: {
      light: {
        iconColor: '{surface.500}',
        removeIconColor: '{surface.500}',
      },
    },
  },
  badge: {
    fontSize: '1.2rem',
    fontWeight: '400',
    minWidth: '1.8rem',
    height: '1.8rem',
    padding: '0.5rem 0.5rem',
    borderRadius: '10rem',
    xl: {
      fontSize: '1.6rem',
      minWidth: '2.2rem',
      height: '2.2rem',
    },
    lg: {
      fontSize: '1.4rem',
      minWidth: '2rem',
      height: '2rem',
    },
    sm: {
      fontSize: '1rem',
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
  fileupload: {
    headerPadding: '1rem 1rem 0.5rem 1rem',
    contentPadding: '0.5rem 1rem 1rem 1rem',
  },
  // Tooltip options not working because of PrimeVue bug
  tooltip: {
    maxWidth: '30rem',
    gutter: '0.5rem',
    colorScheme: {
      light: {
        color: '{text.color}',
        background: '{surface.50}',
      },
    },
  },
  confirmpopup: {
    arrowOffset: 'calc({confirmpopupBorderRadius} * 2.5)',
  },
  dialog: {
    title: {
      fontSize: '1.6rem',
      fontWeight: '600',
    },
    footerGap: '1rem',
  },
  popover: {
    arrowOffset: 'calc({popoverBorderRadius} * 2)',
  },
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
  colorpicker: {
    previewWidth: '3rem',
    previewHeight: '3rem',
  },
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
  paginator: {
    colorScheme: {
      light: {
        background: 'transparent',
      },
    },
  },
  datatable: {
    rowToggleButtonSize: '3rem',
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
  tieredmenu: {
    itemPadding: '1rem 1rem',
    listGap: '0.2rem',
    submenuIconSize: '1.4rem',
  },
}
