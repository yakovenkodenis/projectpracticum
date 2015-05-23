using System;
using System.Runtime.InteropServices;

namespace Autoschool
{
    public class InternetConnection
    {
        [Flags]
        public enum InternetConnectionState
        {
            InternetConnectionModem = 0x1,
            InternetConnectionLan = 0x2,
            InternetConnectionProxy = 0x4,
            InternetRasInstalled = 0x10,
            InternetConnectionOffline = 0x20,
            InternetConnectionConfigured = 0x40
        }

        public bool IsInternetConnected { get; private set; }

        public bool IsUsingModem { get; private set; }

        public bool IsOffline { get; private set; }

        public bool IsUsingLan { get; private set; }

        public bool IsUsingProxy { get; private set; }

        public bool IsRasEnabled { get; private set; }

        [DllImport("WININET", CharSet = CharSet.Auto)]
        static extern bool InternetGetConnectedState(ref InternetConnectionState lpdwFlags, int dwReserved);

        public void Init()
        {
            InternetConnectionState flags = 0;
            IsInternetConnected = InternetGetConnectedState(ref flags, 0);
            IsUsingModem = (flags & InternetConnectionState.InternetConnectionModem) != 0;
            IsUsingLan = (flags & InternetConnectionState.InternetConnectionLan) != 0;
            IsOffline = (flags & InternetConnectionState.InternetConnectionOffline) != 0;
            IsUsingProxy = (flags & InternetConnectionState.InternetConnectionProxy) != 0;
            IsRasEnabled = (flags & InternetConnectionState.InternetRasInstalled) != 0;
        }
    }
}
