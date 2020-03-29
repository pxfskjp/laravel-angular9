export const APP_SELECTORS = {
    HARDWARE: 'hardware',
    SYSTEM: 'system',
    USER: 'user',
    TRANSFER: 'transfer'
}

export const APP_ROUTES = {
   LOGIN: '/login',
   LOGOUT: '/logout',
   REFRESH: '/api/refresh-token',
   HARDWARE: '/api/hardware',
   SYSTEM: '/api/system',
   HARDWARE_SYSTEM: '/api/hardware/system',
   HARDWARE_USER: '/api/hardware/user',
   HARDWARE_LEASE: '/api/hardware/lease',
   USER: '/api/user',
   TRANSFER: '/api/transfer',
}

export const HTTP_ERRORS = {
    UNAUTHORIZED: 401,
    FORBIDDEN: 403,
    VALIDATION_ERROR: 422
};

export const APP_DATE_FORMATS = {
  parse: {
    dateInput: 'YYYY-MM-DD',
  },
  display: {
    dateInput: 'YYYY-MM-DD',
    monthYearLabel: 'MMMM YYYY',
    dateA11yLabel: 'LL',
    monthYearA11yLabel: 'MMMM YYYY',
  },
};

export const APP_TRANSFER_TYPE = {
    LEASE: -1,
    BACK: 1
}
