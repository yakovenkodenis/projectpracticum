using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Interop;

namespace Autoschool
{
    public class QueryEventArgs : EventArgs
    {
        public QueryEventArgs(string s)
        {
            Message = s;
        }

        public string Message { get; private set; }
    }
}
