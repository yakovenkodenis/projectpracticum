using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Autoschool
{
    class Student
    {
        public string Id { get; set; }
        public string Email { get; set; }
        public string Name { get; set; }
        public string Telephone { get; set; }
        public string Address { get; set; }
        public string GroupId { get; set; }
        public string Birthday { get; set; }
        public string Password { get; set; }

        public override string ToString()
        {
            return "Id: " + Id + "\tEmail: " + Email + "\tName: " + Name + "\tTelephone: " + Telephone +
                   "\tAddress: " + Address + "\tGroupId: " + GroupId;
        }
    }
}
