using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Autoschool
{
    public class EditQueryEventArgs : EventArgs
    {
        public EditQueryEventArgs(string name, string text, string id)
        {
            Text = text;
            Name = name;
            Id = id;
        }

        public string Name { get; private set; }
        public string Text { get; private set; }
        public string Id { get; private set; }
    }
}
