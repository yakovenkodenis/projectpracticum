
using System.ComponentModel;
using System.Runtime.CompilerServices;
using Autoschool.Annotations;

namespace Autoschool
{
    public class Query : INotifyPropertyChanged
    {
        private string _id;
        private string _name;
        private string _text;
        private string _autoschool;

        public string Id
        {
            get { return _id; }
            set
            {
                if (value == _id) return;
                _id = value;
                NotifyPropertyChanged("Id");
            }
        }

        public string Name
        {
            get { return _name; }
            set
            {
                if (value == _name) return;
                _name = value;
                NotifyPropertyChanged("Name");
            }
        }

        public string Text
        {
            get { return _text; }
            set
            {
                if (value == _text) return;
                _text = value;
                NotifyPropertyChanged("Text");
            }
        }

        public string Autoschool
        {
            get { return _autoschool; }
            set
            {
                if (value == _autoschool) return;
                _autoschool = value;
                NotifyPropertyChanged("Autoschool");
            }
        }

        public event PropertyChangedEventHandler PropertyChanged;

        private void NotifyPropertyChanged(string info)
        {
            var cached = PropertyChanged;
            if (null != cached)
            {
                cached(this, new PropertyChangedEventArgs(info));
            }
        }
    }
}
