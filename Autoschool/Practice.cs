using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Autoschool
{
    class Practice
    {
        public string PracticeId { get; set; }
        public string StudentId { get; set; }
        public string Lesson { get; set; }
        public string Day { get; set; }

        public override string ToString()
        {
            return "Id: " + PracticeId + "\tStudId: " + StudentId + "\tLesson: " + Lesson + "\tDay: " + Day;
        }
    }
}
