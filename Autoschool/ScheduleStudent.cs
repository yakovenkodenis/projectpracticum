using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Autoschool
{
    class ScheduleStudent
    {
        public string StudentId { get; set; }
        public string ScheduleId { get; set; }

        public override string ToString()
        {
            return "StudId: " + StudentId + "\tSchedId: " + ScheduleId;
        }
    }
}
