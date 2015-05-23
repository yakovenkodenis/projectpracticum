using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Autoschool
{
    class StudentToGroup
    {
        public string GroupId { get; set; }
        public string StudentId { get; set; }

        public override string ToString()
        {
            return "GroupId: " + GroupId + "\tStudentId: " + StudentId;
        }
    }
}
